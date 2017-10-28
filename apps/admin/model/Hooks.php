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
namespace app\admin\model;

use think\Model;

/**
 * 钩子模型
 */

class Hooks extends Model
{
    protected $autoWriteTimestamp = false;
    protected $auto = ['update_time'];
    protected function setUpdateTimeAttr($value)
    {
        return time();
    }

    /**
     * 更新插件里的所有钩子对应的插件
     */
    public function updateHooks($addons_name)
    {
        $addons_class = get_addon_class($addons_name); //获取插件名
        if (!class_exists($addons_class)) {
            $this->error = "未实现{$addons_name}插件的入口文件";
            return false;
        }
        //获取这个插件总的方法列表，数组
        $methods = get_class_methods($addons_class);
        $hooks = $this->column('name');
        $common = array_intersect($hooks, $methods);
        if (!empty($common)) {
            foreach ($common as $hook) {
                $flag = $this->updateAddons($hook, array($addons_name));
                if (false === $flag) {
                    $this->removeHooks($addons_name);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 更新单个钩子处的插件
     * @param  [type] 钩子名称   [description]
     * @param  [type] 插件名称 [description]
     * @return [type]              [description]
     */
    public function updateAddons($hook_name, $addons_name)
    {
        $o_addons = $this->where(['name' => $hook_name])->value('addons');
        if ($o_addons) {
            $o_addons = str2arr($o_addons);
        }
        if ($o_addons) {
            $addons = array_merge($o_addons, $addons_name);
            $addons = array_unique($addons);
        } else {
            $addons = $addons_name;
        }
        $flag = $this->where(['name' => $hook_name])->setField('addons', arr2str($addons));
        if (false === $flag) {
            $this->where(['name' => $hook_name])->setField('addons', arr2str($o_addons));
        }

        return $flag;
    }

    /**
     * 去除插件所有钩子里对应的插件数据
     */
    public function removeHooks($addons_name)
    {
        $addons_class = get_addon_class($addons_name);
        if (!class_exists($addons_class)) {
            return false;
        }
        //获取这个插件总的方法列表，数组
        $methods = get_class_methods($addons_class);
        $hooks = $this->column('name');
        $common = array_intersect($hooks, $methods);
        if ($common) {
            foreach ($common as $hook) {
                $flag = $this->removeAddons($hook, array($addons_name));
                if (false === $flag) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 去除单个钩子里对应的插件数据
     * $hook_name 钩子名称
     * $addons_name 插件名称
     */
    public function removeAddons($hook_name, $addons_name)
    {
        $o_addons = $this->where(['name' => $hook_name])->value('addons');
        $o_addons = str2arr($o_addons);
        if ($o_addons) {
            $addons = array_diff($o_addons, $addons_name);
        } else {
            return true;
        }
        $flag = $this->where(['name' => $hook_name])->setField('addons', arr2str($addons));
        if (false === $flag) {
            $this->where(['name' => $hook_name])->setField('addons', arr2str($o_addons));
        }
        return $flag;
    }

    public function hook_cache()
    {
        $hooks = \think\Db::name('Hooks')->column('name,addons');
        if (empty($hooks)) {
            return false;
        }
        foreach ($hooks as $key => $value) {
            if ($value) {
                $map['status'] = 1;
                $names = explode(',', $value);
                $map['name'] = array('IN', $names);
                $data = \think\Db::name('Addons')->where($map)->column('id,name');
                if ($data) {
                    $addons_arr = array_intersect($names, $data);
                    $addons[$key] = array_map('get_addon_class', $addons_arr);
                }
            }
        }
        cache('hooks', $addons);
        cache('Hooks', $addons);
        return $addons;

    }
}
