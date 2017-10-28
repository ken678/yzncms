<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\addons\controller;

use app\common\controller\Adminbase;
use think\Cookie;
use think\Db;
use think\Loader;

/**
 * 插件管理
 */
class Addons extends Adminbase
{
    public function _initialize()
    {
        parent::_initialize();
        //动态拓展菜单
        $this->assign('_extra_menu', array(
            '已装插件后台' => Loader::model('Addons')->getAdminList(),
        ));
        $this->addons = Loader::model('Addons');
    }

    //显示插件列表
    public function index()
    {
        $addons = $this->addons->getAddonList();
        $page = $this->request->param('page/d', 1);
        $number = 25; // 每页显示
        $voList = Db::name('Addons')->paginate($number, false, array(
            'page' => $page,
        ));
        $_page = $voList->render(); // 获取分页显示
        $voList = array_slice($addons, bcmul($number, $page) - $number, $number);
        $this->assign('_page', $_page);
        $this->assign('_list', $voList);
        // 记录当前列表页的cookie
        Cookie::set('__forward__', $_SERVER['REQUEST_URI']);
        return $this->fetch();
    }

    /**
     * 钩子列表
     */
    public function hooks()
    {
        $list = $this->lists('Hooks', array());
        int_to_string($list, array(
            'type' => [1 => '视图', 2 => '控制器'],
        ));
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('_list', $list);
        return $this->fetch();
    }

    /**
     * 新增钩子页面
     * @return [type] [description]
     */
    public function addhook()
    {
        $this->assign('data', null);
        return $this->fetch('edithook');
    }

    /**
     * 编辑插件页面
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edithook($id)
    {
        $hook = Db::name('Hooks')->field(true)->find($id);
        $this->assign('data', $hook);
        return $this->fetch('edithook');
    }

    /**
     * 编辑钩子
     * @return [type] [description]
     */
    public function updateHook()
    {
        $hookModel = Loader::model('admin/Hooks');
        $data = $this->request->param();
        $result = $this->validate($data, [
            ['name', 'require|unique:hooks', '钩子名称必须|钩子已存在'],
            ['description', 'require', '钩子描述必须'],
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        if (!empty($data['id'])) {
            $flag = $hookModel->allowField(true)->update($data);
            if ($flag !== false) {
                //cache('hooks', null);
                $this->success('更新成功', Cookie('__forward__'));
            } else {
                $this->error('更新失败');
            }
        } else {
            $flag = $hookModel->allowField(true)->save($data);
            if ($flag) {
                //cache('hooks', null);
                $this->success('新增成功', Cookie('__forward__'));
            } else {
                $this->error('新增失败');
            }
        }
    }

    /**
     * 删除钩子
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delhook($id)
    {
        if (Db::name('Hooks')->delete($id) !== false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
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
