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
// | 插件模型
// +----------------------------------------------------------------------
namespace app\addons\model;

use think\Db;
use think\Model;

class Addons extends Model
{
    //插件所处目录路径
    protected $addonsPath = null;
    protected $insert = ['create_time'];
    protected function setCreateTimeAttr($value)
    {
        return time();
    }

    // 模型初始化
    protected function initialize()
    {
        parent::initialize();
        $this->addonsPath = ADDON_PATH;

    }

    /**
     * 获取插件列表
     *
     * @param string $addon_dir
     */
    public function getAddonList($addon_dir = '')
    {
        if (!$addon_dir) {
            $addon_dir = ADDON_PATH;
        }
        //取得模块目录名称
        $dirs = array_map('basename', glob($addon_dir . '*', GLOB_ONLYDIR));
        if ($dirs === false || !file_exists($addon_dir)) {
            $this->error = '插件目录不可读或者不存在';
            return false;
        }
        // 读取数据库插件表
        $addons = $this->order('id desc')->column(true, 'name');
        //遍历插件列表
        foreach ($dirs as $value) {
            //是否已经安装过
            if (!isset($addons[$value])) {
                $class = get_addon_class($value);
                if (!class_exists($class)) {
                    // 实例化插件失败忽略执行
                    $addons[$value]['uninstall'] = -1;
                    continue;
                }
                //获取插件配置
                $obj = new $class();
                $addons[$value] = $obj->info;
                if ($addons[$value]) {
                    $addons[$value]['uninstall'] = 1;
                    unset($addons[$value]['status']);
                    //是否有配置
                    //$config = $obj->getAddonConfig();
                    //$addons[$value]['config'] = $config;
                }
            }
        }
        int_to_string($addons, array('status' => array(-1 => '损坏', 0 => '禁用', 1 => '启用', null => '未安装')));
        $addons = list_sort_by($addons, 'uninstall', 'desc');
        return $addons;
    }
}
