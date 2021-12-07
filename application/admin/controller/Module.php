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

// +----------------------------------------------------------------------
// | 模型管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;
use sys\ModuleService;
use think\Controller;
use think\Db;

class Module extends Adminbase
{
    //系统模块，隐藏
    protected $systemModuleList = ['admin', 'index', 'api', 'attachment', 'common', 'addons', 'template', 'error'];
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->ModuleService = new ModuleService();
        if (!$this->auth->isAdministrator() && in_array($this->request->action(), ['install', 'uninstall', 'local'])) {
            $this->error('非超级管理员禁止操作！');
        }
    }

    //本地模块列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $dirs     = array_map('basename', glob(APP_PATH . '*', GLOB_ONLYDIR));
            $dirs_arr = array_diff($dirs, $this->systemModuleList);
            $list     = [];
            foreach ($dirs_arr as $module) {
                $list[$module] = ModuleService::getInfo($module);
            }
            $result = array("code" => 0, "data" => $list);
            return json($result);
        }
        return $this->fetch();
    }

    //模块安装
    public function install()
    {
        $name = $this->request->param('module');
        if (empty($name)) {
            $this->error('请选择需要安装的模块！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('模块标识错误！');
        }
        if ($this->request->isPost()) {
            try {
                ModuleService::install($name);
            } catch (\Exception $e) {
                cache('Module', null);
                $this->error($e->getMessage());
            }
            $this->success('模块安装成功！一键清理缓存后生效！', url('index'));
        } else {
            $config = ModuleService::getInfo($name);
            //版本检查
            if ($config['adaptation']) {
                if (version_compare(config('version.yzncms_version'), $config['adaptation'], '>=') == false) {
                    $version_check = '<i class="iconfont icon-delete text-danger"></i>';
                } else {
                    $version_check = '<i class="iconfont icon-success text-success"></i>';
                }
            }
            $need_module = [];
            $need_plugin = [];
            $table_check = [];
            // 检查模块依赖
            if (isset($config['need_module']) && !empty($config['need_module'])) {
                $need_module = $this->checkDependence('module', $config['need_module']);
            }
            // 检查插件依赖
            if (isset($config['need_plugin']) && !empty($config['need_plugin'])) {
                $need_plugin = $this->checkDependence('plugin', $config['need_plugin']);
            }
            // 检查目录权限
            // 检查数据表
            if (isset($config['tables']) && !empty($config['tables'])) {
                foreach ($config['tables'] as $table) {
                    if (Db::query("SHOW TABLES LIKE '" . config('database.prefix') . "{$table}'")) {
                        $table_check[] = [
                            'table'  => config('database.prefix') . "{$table}",
                            'result' => '<span class="text-danger">存在同名</span>',
                        ];
                    } else {
                        $table_check[] = [
                            'table'  => config('database.prefix') . "{$table}",
                            'result' => '<i class="iconfont icon-success text-success"></i>',
                        ];
                    }
                }

            }
            $this->assign('need_module', $need_module);
            $this->assign('need_plugin', $need_plugin);
            $this->assign('version_check', $version_check);
            $this->assign('table_check', $table_check);
            $this->assign('config', $config);
            return $this->fetch();

        }
    }

    //模块卸载
    public function uninstall()
    {
        $name = $this->request->param('module');
        if (empty($name)) {
            $this->error('请选择需要卸载的模块！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('模块标识错误！');
        }
        if ($this->request->isPost()) {
            try {
                ModuleService::uninstall($name, true);
            } catch (\Exception $e) {
                cache('Module', null);
                $this->error($e->getMessage());
            }
            $this->success("模块卸载成功！一键清理缓存后生效！", url('index'));
        } else {
            $config = ModuleService::getInfo($name);
            $this->assign('config', $config);
            return $this->fetch();

        }
    }

    /**
     * 检查依赖
     * @param string $type 类型：module/plugin
     * @param array $data 检查数据
     * @return array
     */
    private function checkDependence($type = '', $data = [])
    {
        $need = [];
        foreach ($data as $key => $value) {
            if (!isset($value[2])) {
                $value[2] = '=';
            }
            // 当前版本
            if ($type == 'module') {
                $curr_version = Db::name('Module')->where('module', $value[0])->value('version');
            } else {
                $curr_version = isset((get_addon_info($value[0]))['version']) && 1 == (get_addon_info($value[0]))['status'] ? (get_addon_info($value[0]))['version'] : '';
            }
            $result     = version_compare($curr_version, $value[1], $value[2]);
            $need[$key] = [
                $type          => $value[0],
                'version'      => $curr_version ? $curr_version : '未安装',
                'version_need' => $value[2] . $value[1],
                'result'       => $result ? '<i class="iconfont icon-success text-success"></i>' : '<i class="iconfont icon-delete text-danger"></i>',
            ];
        }
        return $need;
    }

    /**
     * 本地上传
     */
    public function local()
    {
        $file = $this->request->file('file');
        try {
            ModuleService::local($file);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件解压成功，可以进入插件管理进行安装！');
    }

}
