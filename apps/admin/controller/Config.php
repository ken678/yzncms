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

/**
 * 后台配置
 */
class Config extends Adminbase
{

    protected function _initialize()
    {
        parent::_initialize();
        $this->Config = model('Common/Config');
        $configList  =  $this->Config->column('name,value');//获取配置值
        $this->assign('Site', $configList);
    }

    /**
     * 更新配置参数
     */
	public function index()
    {
        if(request()->isPost()){
            if ($this->Config->saveConfig(input('post.'),1)) {
                $this->success("更新成功！");
            } else {
                $error = $this->Config->getError();
                $this->error($error ? $error : "配置更新失败！");
            }
        }else{
            return $this->fetch();
        }
	}

    /**
     * 扩展配置（新增，删除，显示，更新）
     */
    public function extend()
    {
        if(request()->isPost()){
            $action = input('post.action');
            //新增扩展配置
            if ($action == 'add') {
                $data = array(
                    'fieldname' => trim(input('post.fieldname/s')),
                    'type' => trim(input('post.type/s')),
                    'setting' => input('post.setting/a')
                );
                if ($this->Config->extendAdd($data) !== false) {
                    $this->success('扩展配置项添加成功！');
                    return true;
                } else {
                    $error = $this->Config->getError();
                    $this->error($error ? $error : '添加失败！');
                }
            }else{
                //更新扩展项配置
                if ($this->Config->saveConfig(input('post.'),2)) {
                    $this->success("更新成功！");
                } else {
                    $error = $this->Config->getError();
                    $this->error($error ? $error : "配置更新失败！");
                }
            }
        }else{
            $action = input('action');
            $db = db('ConfigField');
            if ($action == 'delete') {
                $fid = input('fid', 0, 'intval');
                if ($this->Config->extendDel($fid)) {
                    $this->success("扩展配置项删除成功！");
                    return true;
                } else {
                    $error = $this->Config->getError();
                    $this->error($error ? $error : "扩展配置项删除失败！");
                }
            }
            $extendList = $db->order(array('fid' => 'DESC'))->select();//获取扩展配置
            $this->assign('extendList', $extendList);
            return $this->fetch();
        }
    }



}