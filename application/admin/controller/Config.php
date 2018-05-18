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

use app\admin\model\Config as ConfigModel;
use app\common\controller\Adminbase;
use think\Db;

class Config extends Adminbase
{
    public $banfie;
    protected function initialize()
    {
        parent::initialize();
        //允许使用的字段列表
        $this->banfie = array("text", "checkbox", "textarea", "radio", "number", "Ueditor", "datetime", "files", "image", "images", "array", "switch", "select");
        $this->ConfigModel = new ConfigModel;
    }

    //配置首页
    public function index($group = 'base')
    {
        /*if ($this->request->isPost()) {
        $list = Db::view('config', 'id,name,title,type,listorder,status,update_time')
        ->where('group', $group)
        ->view('field_type', 'title as ftitle', 'field_type.name=config.type', 'LEFT')
        ->order('listorder,id desc')
        ->select();
        return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $list];
        }*/
        $list = Db::view('config', 'id,name,title,type,listorder,status,update_time')
            ->where('group', $group)
            ->view('field_type', 'title as ftitle', 'field_type.name=config.type', 'LEFT')
            ->order('listorder,id desc')
            ->select();
        $this->assign('list', $list);
        $this->assign('groupArray', self::$Cache['Config']['config_group']);
        $this->assign('group', $group);
        return $this->fetch();
    }

    //配置设置
    public function setting($group = 'base')
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('modelField/a');
            // 查询该分组下所有的配置项名和类型
            $items = $this->ConfigModel->where('group', $group)->where('status', 1)->column('name,type');
            foreach ($items as $name => $type) {
                if (!isset($data[$name])) {
                    switch ($type) {
                        // 开关
                        case 'switch':
                            $data[$name] = 0;
                            break;
                        case 'checkbox':
                            $data[$name] = '';
                            break;
                    }
                } else {
                    // 如果值是数组则转换成字符串，适用于复选框等类型
                    if (is_array($data[$name])) {
                        $data[$name] = implode(',', $data[$name]);
                    }
                    switch ($type) {
                        // 开关
                        case 'switch':
                            $data[$name] = 1;
                            break;
                    }
                }
                if (isset($data[$name])) {
                    $map = array('name' => $name);
                    $this->ConfigModel->where($map)->setField('value', $data[$name]);
                }
            }
            //cache('system_config', null);
            return $this->success('设置更新成功');
        } else {
            $configList = $this->ConfigModel->where('group', $group)
                ->where('status', 1)
                ->order('listorder,id desc')
                ->column('name,title,remark,type,value,options');
            foreach ($configList as &$value) {
                if ($value['options'] != '') {
                    $value['options'] = parse_attr($value['options']);
                }
                if ($value['type'] == 'checkbox') {
                    $value['value'] = empty($value['value']) ? [] : explode(',', $value['value']);
                }
                if ($value['type'] == 'datetime') {
                    $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : $value['value'];
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

    }

    //新增配置
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 1;
            $result = $this->validate($data, 'Config');
            if (true !== $result) {
                return $this->error($result);
            }
            $result = $this->ConfigModel->allowField(['name', 'title', 'group', 'type', 'value', 'options', 'remark', 'listorder', 'status'])->save($data);
            //cache('system_config', null);
            $this->success('配置添加成功~');
        } else {
            $groupArray = self::$Cache['Config']['config_group'];
            $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,ifoption,ifstring');
            $this->assign([
                'groupArray' => $groupArray,
                'fieldType' => $fieldType,
            ]);
            return $this->fetch();
        }
    }

    //编辑配置
    public function edit()
    {
        $id = (int) input('id/d');
        var_dump($id);
        exit();
        if ($this->request->isPost()) {

        } else {
            $groupArray = self::$Cache['Config']['config_group'];
            $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,ifoption,ifstring');
            $info = model('Config')->where('id', $id)->find();
            $this->assign([
                'groupArray' => $groupArray,
                'fieldType' => $fieldType,
                'info' => $info,
            ]);
            return $this->fetch();
        }
    }

    //删除配置
    public function del()
    {
        $id = (int) input('id/d');
        if (!is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if ($this->ConfigModel->where(['id' => $id])->delete()) {
            //cache('system_config', null);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    //设置配置状态
    public function setstate($id, $status)
    {
        $id = (int) input('id/d');
        $status = (int) input('status/d');
        if (($status != 0 && $status != 1) || !is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if ($this->ConfigModel->where('id', $id)->update(['status' => $status])) {
            //cache('system_config', null);
            $this->error('更新成功');
        } else {
            $this->error('更新失败');
        }
    }

}
