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
// | 钩子模型
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;

/**
 * 钩子模型
 */

class Hooks extends Model
{
    protected $autoWriteTimestamp = true;

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

    /**
     * 模块安装，安装行为规则
     * 在模块配置文件中增加 tags 例如：
     *  'tags' => array(
     *                  'login_end' => array(
     *                                    'title' => '会员登陆成功后行为',
     *                                    'remark' => '会员登陆成功后行为',
     *                                    'type' => 1,
     *                                    '具体的规则1',
     *                                    '具体的规则2',
     *                   ),
     *  )
     * @param type $module 模块标识
     * @param type $hooksRule 对应规则
     * @return boolean
     */
    public function moduleHooksInstallation($module, $hooksRule)
    {
        return true;
    }

    /**
     * 卸载模块时删除对应模块安装时创建的规则！
     * @param type $module 模块标识
     * @return boolean
     */
    public function moduleHooksUninstall($module)
    {
        return true;

    }

}
