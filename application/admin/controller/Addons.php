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
// | 插件管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\addons\AddonException;
use think\addons\Service;
use think\Db;
use think\facade\Config;

class Addons extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        if (!$this->auth->isAdministrator() && in_array($this->request->action(), ['install', 'uninstall', 'local'])) {
            $this->error('非超级管理员禁止操作！');
        }
    }

    //显示插件列表
    public function index()
    {
        $limit = $this->request->get("limit/d");
        $page  = $this->request->get("page/d", 1);
        if ($this->request->isAjax()) {
            $addons = get_addon_list();
            $list   = [];
            foreach ($addons as $k => &$v) {
                $config      = get_addon_config($v['name']);
                $v['config'] = $config ? 1 : 0;
            }
            $count = count($addons);
            if ($limit) {
                $addons = array_slice($addons, ($page - 1) * $limit, $limit);
            }
            $result = array("code" => 0, "data" => $addons, 'count' => $count);
            return json($result);
        }
        return $this->fetch();

    }

    /**
     * 设置插件页面
     */
    public function config($name = null)
    {
        $name = $name ? $name : $this->request->get("name");
        if (!$name) {
            $this->error('参数不得为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件名称不正确！');
        }
        if (!is_dir(ADDON_PATH . $name)) {
            $this->error('目录不存在！');
        }
        $info   = get_addon_info($name);
        $config = get_addon_fullconfig($name);
        if (!$info) {
            $this->error('配置不存在！');
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("config/a", [], 'trim');
            if ($params) {
                foreach ($config as $k => &$v) {
                    if (isset($params[$v['name']])) {
                        if ($v['type'] == 'array') {
                            $params[$v['name']] = is_array($params[$v['name']]) ? $params[$v['name']] : (array) json_decode($params[$v['name']],
                                true);
                            $value = $params[$v['name']];
                        } else {
                            $value = is_array($params[$v['name']]) ? implode(',',
                                $params[$v['name']]) : $params[$v['name']];
                        }
                        $v['value'] = $value;
                    } elseif ($v['type'] == 'checkbox' && !isset($params[$v['name']])) {
                        //单独处理多选框为空不传参
                        $v['value'] = '';
                    }
                }
                try {
                    $addon = get_addon_instance($name);
                    //插件自定义配置实现逻辑
                    if (method_exists($addon, 'config')) {
                        $addon->config($name, $config);
                    } else {
                        //更新配置文件
                        set_addon_fullconfig($name, $config);
                        Service::refresh();
                    }
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->success('插件配置成功！');
        }
        $tips = [];
        foreach ($config as $index => &$item) {
            if ($item['name'] == '__tips__') {
                $tips = $item;
                unset($config[$index]);
            }
        }
        $this->assign('data', ['info' => $info, 'config' => $config, 'tips' => $tips]);
        $configFile = ADDON_PATH . $name . DS . 'config.html';
        if (is_file($configFile)) {
            $this->assign('custom_config', $this->view->fetch($configFile));
        }
        return $this->fetch();
    }

    /**
     * 禁用启用.
     */
    public function state()
    {
        $name   = $this->request->param('name');
        $action = $this->request->param('action');
        $force  = $this->request->post("force/d");
        if (!$name) {
            $this->error('参数不得为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件名称不正确');
        }
        try {
            $action = $action == 'enable' ? $action : 'disable';
            //调用启用、禁用的方法
            Service::$action($name, $force);
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('操作成功');
    }

    /**
     * 安装插件
     */
    public function install()
    {
        $name  = $this->request->param('name');
        $force = $this->request->param("force/d");
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        $info = [];
        try {
            $info = Service::install($name, $force);
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件安装成功！清除浏览器缓存和框架缓存后生效！', '', ['addon' => $info]);
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $name       = $this->request->param('name');
        $force      = $this->request->param("force/d");
        $droptables = $this->request->param("droptables/d");
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        //只有开启调试且为超级管理员才允许删除相关数据库
        $tables = [];
        if ($droptables && Config::get("app_debug") && $this->auth->isAdministrator()) {
            $tables = get_addon_tables($name);
        }
        try {
            Service::uninstall($name, $force);
            if ($tables) {
                $prefix = Config::get('database.prefix');
                //删除插件关联表
                foreach ($tables as $index => $table) {
                    Db::execute("DROP TABLE IF EXISTS `{$table}`");
                }
            }
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件卸载成功！清除浏览器缓存和框架缓存后生效！');
    }

    /**
     * 本地上传
     */
    public function local()
    {
        if (!Config::get("app_debug")) {
            $this->error('本地上传安装需要开启调试模式！');
        }
        $file = $this->request->file('file');
        try {
            Service::local($file);
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件解压成功，可以进入插件管理进行安装！');
    }

    /**
     * 测试数据
     */
    public function testdata()
    {
        $name = $this->request->post("name");
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
            $this->error('插件标识错误！');
        }

        try {
            Service::runSQL($name, 'testdata');
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (Exception $e) {
            $this->error($e->getMessage(), $e->getCode());
        }
        $this->success('导入成功');
    }

    /**
     * 获取插件相关表
     */
    public function get_table_list()
    {
        $name = $this->request->post("name");
        if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
            $this->error('插件标识错误！');
        }
        $tables = get_addon_tables($name);
        $prefix = Config::get('database.prefix');
        $tables = array_values($tables);
        $this->success('', null, ['tables' => $tables]);
    }
}
