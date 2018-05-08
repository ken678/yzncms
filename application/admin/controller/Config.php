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
// | 系统配置
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Db;

class Config extends Adminbase
{
    //配置首页
    public function index($group = 'base')
    {
        if ($this->request->isPost()) {
            $list = Db::view('config', 'id,name,title,type,listorder,status,update_time')
                ->where('group', $group)
                ->view('field_type', 'title as ftitle', 'field_type.name=config.type', 'LEFT')
                ->order('listorder,id desc')
                ->select();
            return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $list];
        }
        $this->assign('groupArray', self::$Cache['Config']['config_group']);
        $this->assign('group', $group);
        return $this->fetch();
    }

    //配置设置
    public function setting($group = 'base')
    {
        $configList = model('Config')->where('group', $group)
            ->where('status', 1)
            ->order('listorder,id desc')
            ->column('name,title,remark,type,value,extra');
        foreach ($configList as &$value) {
            if ($value['extra'] != '') {
                $value['extra'] = parse_attr($value['extra']);
            }
            if ($value['type'] == 'checkbox') {
                $value['value'] = empty($value['value']) ? [] : explode(',', $value['value']);
            }
            if ($value['type'] == 'datetime') {
                $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $value['value']);
            }
            if ($value['type'] == 'date') {
                $value['value'] = empty($value['value']) ? '' : date('Y-m-d', $value['value']);
            }
            if ($value['type'] == 'image') {
                $value['param'] = ['dir' => 'images', 'module' => 'admin', 'watermark' => 0];
            }
            if ($value['type'] == 'images') {
                $value['param'] = ['dir' => 'images', 'module' => 'admin', 'watermark' => 0];
                if (!empty($value['value'])) {
                    $value['value'] .= ',';
                }
            }
            if ($value['type'] == 'files') {
                $value['param'] = ['dir' => 'files', 'module' => 'admin'];
                if (!empty($value['value'])) {
                    $value['value'] .= ',';
                }
            }
            if ($value['type'] == 'Ueditor') {
                $value['value'] = htmlspecialchars_decode($value['value']);
            }
            $value['fieldArr'] = 'modelField';
        }
        $this->assign('groupArray', self::$Cache['Config']['config_group']);
        $this->assign('fieldList', $configList);
        $this->assign('group', $group);
        return $this->fetch();

    }

    //删除配置
    public function del()
    {
        $id = (int) input('id/d');
        if (!is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if (model('Config')->get($id)->delete()) {
            //cache('system_config', null);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    //设置配置状态
    public function setstate($id, $status)
    {
        if (($status != 0 && $status != 1) || !is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if (model('Config')->where('id', $id)->update(['status' => $status])) {
            //cache('system_config', null);
            $this->error('更新成功');
        } else {
            $this->error('更新失败');
        }
    }

}
