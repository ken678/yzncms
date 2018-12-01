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

use app\addons\model\Addons as Addons_model;
use app\common\controller\Adminbase;
use think\Db;

class Addons extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->addons = new Addons_model;
    }

    //显示插件列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $addons = $this->addons->getAddonList();
            $result = array("code" => 0, "data" => $addons);
            return json($result);
        }
        return $this->fetch();

    }

    public function hooks()
    {
        var_dump(222);
    }

    /**
     * 设置插件页面
     */
    public function config()
    {
        $addonId = $this->request->param('id/d');
        if (empty($addonId)) {
            $this->error('请选择需要操作的插件！');
        }
        //获取插件信息
        $addon = $this->addons->where(array('id' => $addonId))->find()->toArray();
        if (empty($addon)) {
            $this->error('该插件没有安装！');
        }
        //实例化插件入口类
        $addon_class = get_addon_class($addon['name']);
        if (!class_exists($addon_class)) {
            trace("插件{$addon['name']}无法实例化,", 'ADDONS', 'ERR');
        }
        $addonObj = new $addon_class();
        $addon['addon_path'] = $addonObj->addon_path;
        $addon['custom_config'] = isset($addonObj->custom_config) ? $addonObj->custom_config : '';
        $db_config = $addon['config'];
        //载入插件配置数组
        $addon['config'] = include $addonObj->config_file;
        if ($db_config) {
            $db_config = json_decode($db_config, true);
            foreach ($addon['config'] as $key => $value) {
                if ($value['type'] != 'group') {
                    $addon['config'][$key]['value'] = isset($db_config[$key]) ? $db_config[$key] : '';
                } else {
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
            }
        }
        $this->assign('data', $addon);
        if ($addon['custom_config']) {
            //加载配置文件config.html
            $this->assign('custom_config', $this->fetch($addon['addon_path'] . $addon['custom_config']));
        }
        return $this->fetch();
    }

    /**
     * 保存插件设置
     */
    public function saveConfig()
    {
        $id = $this->request->param('id/d');
        //获取插件信息
        $info = $this->addons->where(array('id' => $id))->find();
        if (empty($info)) {
            $this->error('该插件没有安装！');
        }
        $config = $this->request->param('config/a');
        $flag = Db::name('Addons')->where(['id' => $id])->setField('config', json_encode($config));
        if ($flag !== false) {
            //更新插件缓存
            $this->addons->addons_cache();
            $this->success('保存成功', Cookie('__forward__'));
        } else {
            $this->error('保存失败');
        }
    }

    //启用插件
    public function enable()
    {
        $id = $this->request->param('id/d');
        //cache('Hooks', null);
        if ($this->addons->save(['status' => 1], ['id' => $id])) {
            $this->success('启用成功');
        } else {
            $this->error('启用失败');
        }
    }

    //禁用插件
    public function disable()
    {
        $id = $this->request->param('id/d');
        //cache('Hooks', null);
        if ($this->addons->save(['status' => 0], ['id' => $id])) {
            $this->success('禁用成功');
        } else {
            $this->error('禁用失败');
        }
    }

    /**
     * 安装插件
     */
    public function install()
    {
        $addonName = $this->request->param('addon_name');
        if (empty($addonName)) {
            $this->error('请选择需要安装的插件！');
        }
        if ($this->addons->installAddon($addonName)) {
            $this->success('插件安装成功！', url('Addons/index'));
        } else {
            $error = $this->addons->getError();
            $this->error($error ? $error : '插件安装失败！');
        }
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $addonId = $this->request->param('id/d');
        if (empty($addonId)) {
            $this->error('请选择需要卸载的插件！');
        }
        if ($this->addons->uninstallAddon($addonId)) {
            $this->success('插件卸载成功！', url('Addons/index'));
        } else {
            $error = $this->addons->getError();
            $this->error($error ? $error : '插件卸载失败！');
        }
    }

}
