<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
// | Original reference: https://gitee.com/karson/fastadmin
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 菜单主程序
// +----------------------------------------------------------------------
namespace app\admin\command;

use app\admin\model\AuthRule;
use ReflectionClass;
use ReflectionMethod;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Exception;
use think\facade\Cache;
use think\facade\Config;

class Menu extends Command
{
    protected $model = null;

    protected function configure()
    {
        $this
            ->setName('menu')
            ->addOption('controller', 'c', Option::VALUE_REQUIRED | Option::VALUE_IS_ARRAY, 'controller name,use \'all-controller\' when build all menu', null)
            ->addOption('delete', 'd', Option::VALUE_OPTIONAL, 'delete the specified menu', '')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force delete menu,without tips', null)
            ->addOption('equal', 'e', Option::VALUE_OPTIONAL, 'the controller must be equal', null)
            ->setDescription('Build auth menu from controller');
        //要执行的controller必须一样，不适用模糊查询
    }

    protected function execute(Input $input, Output $output)
    {
        $this->model = new AuthRule();
        $adminPath   = dirname(__DIR__) . DS;
        //控制器名
        $controller = $input->getOption('controller') ?: '';
        if (!$controller) {
            throw new Exception("please input controller name");
        }
        $force = $input->getOption('force');
        //是否为删除模式
        $delete = $input->getOption('delete');
        //是否控制器完全匹配
        $equal = $input->getOption('equal');

        if ($delete) {
            $ids  = [];
            $list = $this->model->where(function ($query) use ($controller, $equal) {
                foreach ($controller as $index => $item) {
                    if (stripos($item, '_') !== false) {
                        $item = parse_name($item, 1);
                    }
                    if (stripos($item, '/') !== false) {
                        $controllerArr = explode('/', $item);
                        end($controllerArr);
                        $key                 = key($controllerArr);
                        $controllerArr[$key] = parse_name($controllerArr[$key]);
                    } else {
                        $controllerArr = [parse_name($item)];
                    }
                    $item = str_replace('_', '\_', implode('.', $controllerArr));
                    if ($equal) {
                        $query->whereOr('name', 'eq', $item);
                    } else {
                        $query->whereOr('name', 'like', strtolower($item) . "%");
                    }
                }
            })->select();
            foreach ($list as $k => $v) {
                $output->warning($v->name);
                $ids[] = $v->id;
            }
            if (!$ids) {
                throw new Exception("There is no menu to delete");
            }
            if (!$force) {
                $question = $output->confirm($input, "Are you sure you want to delete all those menu?  Type 'yes' to continue: ", false);
                if (!$question) {
                    throw new Exception("Operation is aborted!");
                }
            }
            AuthRule::destroy($ids);

            Cache::delete("__menu__");
            $output->info("Delete Successed");
            return;
        }

        foreach ($controller as $index => $item) {
            if (stripos($item, '_') !== false) {
                $item = parse_name($item, 1);
            }
            if (stripos($item, '/') !== false) {
                $controllerArr = explode('/', $item);
                end($controllerArr);
                $key                 = key($controllerArr);
                $controllerArr[$key] = ucfirst($controllerArr[$key]);
            } else {
                $controllerArr = [ucfirst($item)];
            }
            $adminPath = dirname(__DIR__) . DS . 'controller' . DS . implode(DS, $controllerArr) . '.php';
            if (!is_file($adminPath)) {
                $output->error("controller not found");
                return;
            }
            $this->importRule($item);
        }

        Cache::delete("__menu__");
        $output->info("Build Successed!");
    }

    protected function importRule($controller)
    {
        $controller = str_replace('\\', '/', $controller);
        if (stripos($controller, '/') !== false) {
            $controllerArr = explode('/', $controller);
            end($controllerArr);
            $key                 = key($controllerArr);
            $controllerArr[$key] = ucfirst($controllerArr[$key]);
        } else {
            $key           = 0;
            $controllerArr = [ucfirst($controller)];
        }
        $classSuffix = Config::get('route.controller_suffix') ? ucfirst(Config::get('route.url_controller_layer')) : '';
        $className   = "\\app\\admin\\controller\\" . implode("\\", $controllerArr) . $classSuffix;

        $pathArr = $controllerArr;
        array_unshift($pathArr, '', 'app', 'admin', 'controller');
        $classFile    = app()->getRootPath() . implode(DS, $pathArr) . $classSuffix . ".php";
        $classContent = file_get_contents($classFile);
        $uniqueName   = uniqid("YznCMS") . $classSuffix;
        $classContent = str_replace("class " . $controllerArr[$key] . $classSuffix . " ", 'class ' . $uniqueName . ' ', $classContent);
        $classContent = preg_replace("/namespace\s(.*);/", 'namespace ' . __NAMESPACE__ . ";", $classContent);

        //临时的类文件
        $tempClassFile = __DIR__ . DS . $uniqueName . ".php";
        file_put_contents($tempClassFile, $classContent);

        $className = "\\app\\admin\\command\\" . $uniqueName;

        //删除临时文件
        register_shutdown_function(function () use ($tempClassFile) {
            if ($tempClassFile) {
                //删除临时文件
                @unlink($tempClassFile);
            }
        });

        //反射机制调用类的注释和方法名
        $reflector = new ReflectionClass($className);

        //只匹配公共的方法
        $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

        $classComment = $reflector->getDocComment();
        //判断是否有启用软删除
        $softDeleteMethods = ['destroy', 'restore', 'recyclebin'];
        $withSofeDelete    = false;
        $modelRegexArr     = ["/\\\$this\->model\s*=\s*model\(['|\"](\w+)['|\"]\);/", "/\\\$this\->model\s*=\s*new\s+([a-zA-Z\\\]+);/"];
        $modelRegex        = preg_match($modelRegexArr[0], $classContent) ? $modelRegexArr[0] : $modelRegexArr[1];

        preg_match_all($modelRegex, $classContent, $matches);
        if (isset($matches[1]) && isset($matches[1][0]) && $matches[1][0]) {
            $model = model($matches[1][0]);
            if (in_array('trashed', get_class_methods($model))) {
                $withSofeDelete = true;
            }
        }
        //忽略的类
        if (stripos($classComment, "@internal") !== false) {
            return;
        }
        preg_match_all('#(@.*?)\n#s', $classComment, $annotations);
        $controllerIcon   = 'iconfont icon-list-unordered';
        $controllerRemark = '';
        //判断注释中是否设置了icon值
        if (isset($annotations[1])) {
            foreach ($annotations[1] as $tag) {
                if (stripos($tag, '@icon') !== false) {
                    $controllerIcon = substr($tag, stripos($tag, ' ') + 1);
                }
                if (stripos($tag, '@remark') !== false) {
                    $controllerRemark = substr($tag, stripos($tag, ' ') + 1);
                }
            }
        }
        //过滤掉其它字符
        $controllerTitle = trim(preg_replace(['/^\/\*\*(.*)[\n\r\t]/u', '/[\s]+\*\//u', '/\*\s@(.*)/u', '/[\s|\*]+/u'], '', $classComment));

        //先导入菜单的数据
        $pid = 0;
        foreach ($controllerArr as $k => $v) {
            $key = $k + 1;
            //驼峰转下划线
            $controllerNameArr = array_slice($controllerArr, 0, $key);
            foreach ($controllerNameArr as &$val) {
                $val = strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $val), "_"));
            }
            unset($val);
            $name      = implode('.', $controllerNameArr);
            $title     = (!isset($controllerArr[$key]) ? $controllerTitle : '');
            $icon      = (!isset($controllerArr[$key]) ? $controllerIcon : 'iconfont icon-list-unordered');
            $remark    = (!isset($controllerArr[$key]) ? $controllerRemark : '');
            $title     = $title ? $title : $v;
            $rulemodel = $this->model->find(['name' => $name]);
            if (!$rulemodel) {
                $pid = $this->model
                    ->insertGetId([
                        'parentid' => $pid,
                        'name'     => $name,
                        'title'    => $title,
                        'icon'     => $icon,
                        'remark'   => $remark,
                        'ismenu'   => 1,
                        'status'   => 1,
                    ]);
            } else {
                $pid = $rulemodel->id;
            }
        }
        $ruleArr = [];
        foreach ($methods as $m => $n) {
            //过滤特殊的类
            if (in_array($n->name, ['initialize', '_empty', 'registerMiddleware']) || substr($n->name, 0, 2) == '__') {
                continue;
            }
            //未启用软删除时过滤相关方法
            if (!$withSofeDelete && in_array($n->name, $softDeleteMethods)) {
                continue;
            }
            //只匹配符合的方法
            if (!preg_match('/^(\w+)' . Config::get('route.action_suffix') . '/', $n->name, $matchtwo)) {
                unset($methods[$m]);
                continue;
            }
            $comment = $reflector->getMethod($n->name)->getDocComment();
            //忽略的方法
            if (stripos($comment, "@internal") !== false) {
                continue;
            }
            //过滤掉其它字符
            $comment = preg_replace(['/^\/\*\*(.*)[\n\r\t]/u', '/[\s]+\*\//u', '/\*\s@(.*)/u', '/[\s|\*]+/u'], '', $comment);

            $title = $comment ? $comment : ucfirst($n->name);

            //获取主键，作为AuthRule更新依据
            $id = $this->getAuthRulePK($name . "/" . strtolower($n->name));

            $ruleArr[] = ['id' => $id, 'parentid' => $pid, 'name' => $name . "/" . strtolower($n->name), 'icon' => 'iconfont icon-circle-line', 'title' => $title, 'ismenu' => 0, 'status' => 1];
        }
        $this->model->insertAll($ruleArr);
    }

    //获取主键
    protected function getAuthRulePK($name)
    {
        if (!empty($name)) {
            $id = $this->model
                ->where('name', $name)
                ->value('id');
            return $id ? $id : null;
        }
    }
}
