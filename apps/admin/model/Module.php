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
 * 模块模型
 * @package app\admin\model
 */
class Module extends Model
{
    //模块所处目录路径
    protected $appPath = APP_PATH;
    //已安装模块列表
    protected $moduleList = array();
    //系统模块，隐藏
    protected $systemModuleList = array('admin', 'attachment', 'common', 'content', 'home');
    /**
     * 获取所有模块信息
     */
    public function getAll()
    {
        $dirs = array_map('basename', glob($this->appPath . '*', GLOB_ONLYDIR));
        if ($dirs === false || !file_exists(APP_PATH)) {
            $this->error = '模块目录不可读或者不存在';
            return false;
        }
        // 正常模块(包括已安装和未安装)
        $dirs = array_diff($dirs, $this->systemModuleList);

        // 读取数据库已经安装模块表
        $modules = $this->order('listorder asc')->select();
        return $modules;

    }

}
