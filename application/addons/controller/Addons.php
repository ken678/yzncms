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
namespace app\addons\controller;

use app\common\controller\Adminbase;
use sys\AddonService;

class Addons extends Adminbase
{
    //显示插件列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $addons = get_addon_list();
            $list   = [];
            foreach ($addons as $k => &$v) {
                $config      = get_addon_config($v['name']);
                $v['config'] = $config ? 1 : 0;
            }
            $result = array("code" => 0, "data" => $addons);
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
                    }
                }
                try {
                    //更新配置文件
                    set_addon_fullconfig($name, $config);
                    //AddonService::refresh();
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->success('插件配置成功！');
        }
        $this->assign('data', ['info' => $info, 'config' => $config]);
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

        if (!$name) {
            $this->error('参数不得为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件名称不正确');
        }
        try {
            $action = $action == 'enable' ? $action : 'disable';
            //调用启用、禁用的方法
            AddonService::$action($name, true);
            //Cache::delete('__menu__');
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
        $name = $this->request->param('name');
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        try {
            AddonService::install($name);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件安装成功！清除浏览器缓存和框架缓存后生效！');
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $name = $this->request->param('name');
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        try {
            AddonService::uninstall($name, true);
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
        $info = [];
        $file = $this->request->file('file');
        try {
            $info = AddonService::local($file);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件解压成功，可以进入插件管理进行安装！', url('index'));
    }
}
