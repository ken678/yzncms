<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | crud主程序
// +----------------------------------------------------------------------

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\Exception;
use think\exception\ErrorException;
use think\facade\Config;
use think\Loader;

class Crud extends Command
{
    protected $stubList = [];

    //关键保留字
    protected $internalKeywords = [
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
    ];

    /**
     * 受保护的系统表, crud不会生效
     */
    protected $systemTables = [
        'admin',
        'adminlog',
        'attachment',
        'auth_group',
        'auth_rule',
        'cache',
        'config',
        'field_type',
        'model',
        'model_field',
        'terms',
        'ems',
        'sms',
    ];

    /**
     * 添加时间字段
     * @var string
     */
    protected $createTimeField = 'create_time';

    /**
     * 更新时间字段
     * @var string
     */
    protected $updateTimeField = 'update_time';

    /**
     * 软删除时间字段
     * @var string
     */
    protected $deleteTimeField = 'delete_time';

    protected function configure()
    {
        $this->setName('crud')
            ->addOption('table', 't', Option::VALUE_REQUIRED, 'table name without prefix', null)
            ->addOption('controller', 'c', Option::VALUE_OPTIONAL, 'controller name', null)
            ->addOption('model', 'm', Option::VALUE_OPTIONAL, 'model name', null)
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override or force delete,without tips', null)
            ->addOption('local', 'l', Option::VALUE_OPTIONAL, 'local model', 1)
            ->addOption('db', null, Option::VALUE_OPTIONAL, 'database config name', 'database')
            ->setDescription('Build CRUD controller and model from table');

    }

    protected function execute(Input $input, Output $output)
    {
        //数据库
        $db = $input->getOption('db');
        //表名
        $table = $input->getOption('table') ?: '';
        if (!$table) {
            throw new Exception('table name can\'t empty');
        }
        //强制覆盖
        $force = $input->getOption('force');
        //自定义控制器
        $controller = $input->getOption('controller');
        //自定义模型
        $model = $input->getOption('model');
        $model = $model ?: $controller;
        //验证器类
        $validate = $model;
        //是否为本地model,为0时表示为全局model将会把model放在app/common/model中
        $local = $input->getOption('local');

        $dbconnect = Db::connect('mysql');
        $dbname    = Config::get($db . '.database');
        $prefix    = Config::get($db . '.prefix');
        //系统表无法生成，防止后台错乱
        if (in_array(str_replace($prefix, "", $table), $this->systemTables)) {
            throw new Exception('system table can\'t be crud');
        }

        //模块
        $moduleName         = 'admin';
        $modelModuleName    = $local ? $moduleName : 'common';
        $validateModuleName = $local ? $moduleName : 'common';

        //检查主表
        $modelName          = $table          = stripos($table, $prefix) === 0 ? substr($table, strlen($prefix)) : $table;
        $modelTableType     = 'table';
        $modelTableTypeName = $modelTableName = $modelName;
        $modelTableInfo     = $dbconnect->query("SHOW TABLE STATUS LIKE '{$modelTableName}'", [], true);
        if (!$modelTableInfo) {
            $modelTableType = 'name';
            $modelTableName = $prefix . $modelName;
            $modelTableInfo = $dbconnect->query("SHOW TABLE STATUS LIKE '{$modelTableName}'", [], true);
            if (!$modelTableInfo) {
                throw new Exception("table not found");
            }
        }
        $modelTableInfo = $modelTableInfo[0];

        //控制器
        list($controllerNamespace, $controllerName, $controllerFile, $controllerArr) = $this->getControllerData($moduleName, $controller, $table);
        //模型
        list($modelNamespace, $modelName, $modelFile, $modelArr) = $this->getModelData($modelModuleName, $model, $table);
        //验证器
        list($validateNamespace, $validateName, $validateFile, $validateArr) = $this->getValidateData($validateModuleName, $validate, $table);

        //非覆盖模式时如果存在控制器文件则报错
        if (is_file($controllerFile) && !$force) {
            throw new Exception("controller already exists!\nIf you need to rebuild again, use the parameter --force=true ");
        }

        //非覆盖模式时如果存在模型文件则报错
        if (is_file($modelFile) && !$force) {
            throw new Exception("model already exists!\nIf you need to rebuild again, use the parameter --force=true ");
        }

        //非覆盖模式时如果存在验证文件则报错
        if (is_file($validateFile) && !$force) {
            throw new Exception("validate already exists!\nIf you need to rebuild again, use the parameter --force=true ");
        }

        //从数据库中获取表字段信息
        $sql = "SELECT * FROM `information_schema`.`columns` "
            . "WHERE TABLE_SCHEMA = ? AND table_name = ? "
            . "ORDER BY ORDINAL_POSITION";
        //加载主表的列
        $columnList = $dbconnect->query($sql, [$dbname, $modelTableName]);
        $fieldArr   = [];
        foreach ($columnList as $k => $v) {
            $fieldArr[] = $v['COLUMN_NAME'];
        }
        try {
            $appendAttrList       = [];
            $controllerAssignList = [];
            //表注释
            $tableComment = $modelTableInfo['Comment'];
            $tableComment = mb_substr($tableComment, -1) == '表' ? mb_substr($tableComment, 0, -1) . '管理' : $tableComment;

            $data = [
                'controllerNamespace'     => $controllerNamespace,
                'controllerName'          => $controllerName,
                'controllerIndex'         => '',
                'modelConnection'         => $db == 'database' ? '' : "protected \$connection = '{$db}';",
                'modelNamespace'          => $modelNamespace,
                'modelName'               => $modelName,
                'modelTableType'          => $modelTableType,
                'modelTableTypeName'      => $modelTableTypeName,
                'validateName'            => $validateName,
                'validateNamespace'       => $validateNamespace,
                'softDeleteClassPath'     => in_array($this->deleteTimeField, $fieldArr) ? "use think\model\concern\SoftDelete;" : '',
                'softDelete'              => in_array($this->deleteTimeField, $fieldArr) ? "use SoftDelete;" : '',
                'tableComment'            => $tableComment,
                'modelAutoWriteTimestamp' => in_array($this->createTimeField, $fieldArr) || in_array($this->updateTimeField, $fieldArr) ? "'int'" : 'false',
                'createTime'              => in_array($this->createTimeField, $fieldArr) ? "'{$this->createTimeField}'" : 'false',
                'updateTime'              => in_array($this->updateTimeField, $fieldArr) ? "'{$this->updateTimeField}'" : 'false',
                'deleteTime'              => in_array($this->deleteTimeField, $fieldArr) ? "'{$this->deleteTimeField}'" : 'false',
                'appendAttrList'          => implode(",\n", $appendAttrList),
            ];
            // 生成控制器文件
            $this->writeToFile('controller', $data, $controllerFile);
            // 生成模型文件
            $this->writeToFile('model', $data, $modelFile);
            // 生成验证文件
            $this->writeToFile('validate', $data, $validateFile);

        } catch (ErrorException $e) {
            throw new Exception("Code: " . $e->getCode() . "\nLine: " . $e->getLine() . "\nMessage: " . $e->getMessage() . "\nFile: " . $e->getFile());
        }
        $output->info("Build Successed");
    }

    /**
     * 获取控制器相关信息
     * @param $module
     * @param $controller
     * @param $table
     * @return array
     */
    protected function getControllerData($module, $controller, $table)
    {
        return $this->getParseNameData($module, $controller, $table, 'controller');
    }

    /**
     * 获取模型相关信息
     * @param $module
     * @param $model
     * @param $table
     * @return array
     */
    protected function getModelData($module, $model, $table)
    {
        return $this->getParseNameData($module, $model, $table, 'model');
    }

    /**
     * 获取验证器相关信息
     * @param $module
     * @param $validate
     * @param $table
     * @return array
     */
    protected function getValidateData($module, $validate, $table)
    {
        return $this->getParseNameData($module, $validate, $table, 'validate');
    }

    /**
     * 获取已解析相关信息
     * @param string $module 模块名称
     * @param string $name   自定义名称
     * @param string $table  数据表名
     * @param string $type   解析类型，本例中为controller、model、validate
     * @return array
     */
    protected function getParseNameData($module, $name, $table, $type)
    {
        $arr = [];
        if (!$name) {
            $parseName = Loader::parseName($table, 1);
            $name      = str_replace('_', '/', $table);
        }

        $name      = str_replace(['.', '/', '\\'], '/', $name);
        $arr       = explode('/', $name);
        $parseName = ucfirst(array_pop($arr));
        $parseArr  = $arr;
        array_push($parseArr, $parseName);
        //类名不能为内部关键字
        if (in_array(strtolower($parseName), $this->internalKeywords)) {
            throw new Exception('Unable to use internal variable:' . $parseName);
        }
        $appNamespace   = 'app';
        $parseNamespace = "{$appNamespace}\\{$module}\\{$type}" . ($arr ? "\\" . implode("\\", $arr) : "");
        $moduleDir      = APP_PATH . $module . DS;
        $parseFile      = $moduleDir . $type . DS . ($arr ? implode(DS, $arr) . DS : '') . $parseName . '.php';
        return [$parseNamespace, $parseName, $parseFile, $parseArr];
    }

    /**
     * 写入到文件
     * @param string $name
     * @param array  $data
     * @param string $pathname
     * @return mixed
     */
    protected function writeToFile($name, $data, $pathname)
    {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $content = $this->getReplacedStub($name, $data);

        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }
        return file_put_contents($pathname, $content);
    }

    /**
     * 获取替换后的数据
     * @param string $name
     * @param array  $data
     * @return string
     */
    protected function getReplacedStub($name, $data)
    {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $search = $replace = [];
        foreach ($data as $k => $v) {
            $search[]  = "{%{$k}%}";
            $replace[] = $v;
        }
        $stubname = $this->getStub($name);
        if (isset($this->stubList[$stubname])) {
            $stub = $this->stubList[$stubname];
        } else {
            $this->stubList[$stubname] = $stub = file_get_contents($stubname);
        }
        $content = str_replace($search, $replace, $stub);
        return $content;
    }

    /**
     * 获取基础模板
     * @param string $name
     * @return string
     */
    protected function getStub($name)
    {
        return __DIR__ . DS . 'Crud' . DS . 'stubs' . DS . $name . '.stub';
    }
}
