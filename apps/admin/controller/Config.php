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
namespace app\admin\controller;

use app\common\controller\Adminbase;
use app\common\model\Configs as ConfigModel;
use think\Db;
use think\Request;

/**
 * 后台配置
 */
class Config extends Adminbase
{
    //配置初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->Config = new ConfigModel;
        $configList = $this->Config->column('name,value'); //获取系统基本配置值
        $this->assign('Site', $configList);
    }

    // 配置参数列表及更新
    public function index()
    {
        if (Request::instance()->isPost()) {
            if ($this->Config->saveConfig(input('post.'), 1)) {
                $this->success("更新成功！");
            } else {
                $error = $this->Config->getError();
                $this->error($error ? $error : "配置更新失败！");
            }
        } else {
            return $this->fetch();
        }
    }

    //扩展配置（新增，删除，显示，更新）
    public function extend()
    {
        if (Request::instance()->isPost()) {
            $action = Request::instance()->param('action');
            //新增扩展配置
            if ($action == 'add') {
                $data = array(
                    'fieldname' => trim(Request::instance()->param('fieldname/s')),
                    'type' => trim(Request::instance()->param('type/s')),
                    'setting' => Request::instance()->param('setting/a'),
                );
                if ($this->Config->extendAdd($data) !== false) {
                    $this->success('扩展配置项添加成功！');
                    return true;
                } else {
                    $error = $this->Config->getError();
                    $this->error($error ? $error : '添加失败！');
                }
            } else {
                //更新扩展项配置
                if ($this->Config->saveConfig(Request::instance()->post(), 2)) {
                    $this->success("更新成功！");
                } else {
                    $error = $this->Config->getError();
                    $this->error($error ? $error : "配置更新失败！");
                }
            }
        } else {
            $action = Request::instance()->param('action');
            $db = Db::name('ConfigField');
            //删除扩展配置
            if ($action == 'delete') {
                $fid = Request::instance()->param('fid', 0, 'intval');
                if ($this->Config->extendDel($fid)) {
                    $this->success("扩展配置项删除成功！");
                    return true;
                } else {
                    $error = $this->Config->getError();
                    $this->error($error ? $error : "扩展配置项删除失败！");
                }
            }
            $extendList = $db->order(array('fid' => 'DESC'))->select(); //获取扩展配置
            $this->assign('extendList', $extendList);
            return $this->fetch();
        }
    }

    public function add()
    {
        return $this->fetch();
    }
}
