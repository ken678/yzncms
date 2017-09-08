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
        $dirs_arr = array_diff($dirs, $this->systemModuleList);

        // 读取数据库已经安装模块表
        $modules = $this->order('listorder asc')->column(true, 'module');

        //取得已安装模块列表
        $moduleList = array();
        foreach ($modules as $v) {
            $moduleList[$v['module']] = $v;
            //检查是否系统模块，如果是，直接不显示
            if ($v['iscore']) {
                $key = array_keys($dirs_arr, $v['module']);
                unset($dirs_arr[$key[0]]);
            }
        }

        //数量
        //$count = count($dirs_arr);
        
        foreach ($dirs_arr as $module) {
            $list[$module] = $this->getInfoFromFile($module);
        }

        $result = ['moduleList' => $modules, 'modules' => $list];

        return $result;

    }

    /**
     * 从文件获取模块信息
     * @param string $name 模块名称
     * @author 蔡伟明 <314013107@qq.com>
     * @return array|mixed
     */
    public  function getInfoFromFile($moduleName = '')
    {
        if (empty($moduleName)) {
            $this->error = '模块名称不能为空！';
            return false;
        }
        $config = array(
            //模块目录
            'module' => $moduleName,
            //模块名称
            'modulename' => $moduleName,
            //模块介绍地址
            'address' => '',
            //模块简介
            'introduce' => '',
            //模块作者
            'author' => '',
            //作者地址
            'authorsite' => '',
            //作者邮箱
            'authoremail' => '',
            //版本号，请不要带除数字外的其他字符
            'version' => '',
            //适配最低yzncms版本，
            'adaptation' => '',
            //签名
            'sign' => '',
            //依赖模块
            'depend' => array(),
            //行为
            'tags' => array(),
            //缓存
            'cache' => array(),
        );

        // 从配置文件获取
        if (is_file($this->appPath. $moduleName . '/info.php')) {
            $moduleConfig = include $this->appPath. $moduleName . '/info.php';
            $config = array_merge($config, $moduleConfig);
        }

        //检查是否安装，如果安装了，加载模块安装后的相关配置信息
        if ($this->isInstall($moduleName)) {
            $moduleList = cache('Module');
            $config = array_merge($moduleList[$moduleName], $config);
        }
        return $config;
    }

    /**
     * 是否已经安装
     * @param type $moduleName 模块名(目录名)
     * @return boolean
     */
    public function isInstall($moduleName = '')
    {
        if (empty($moduleName)) {
            $this->error = '模块名称不能为空！';
            return false;
        }
        if ('content' == $moduleName) {
            return true;
        }
        $moduleList = cache('Module');
        return (isset($moduleList[$moduleName]) && $moduleList[$moduleName]) ? true : false;
    }

}
