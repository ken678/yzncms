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
    protected $modelValidate      = true;
    protected $modelSceneValidate = true;
    protected function initialize()
    {
        parent::initialize();
        $filepath = APP_PATH . 'admin' . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;
        $custom   = str_replace($filepath . DIRECTORY_SEPARATOR, '', glob($filepath . DIRECTORY_SEPARATOR . 'custom*'));
        //允许使用的字段列表
        $this->banfie = ["text", "checkbox", "textarea", "radio", "number", "datetime", "image", "images", "array", "switch", "select", "selects", "selectpage", "Ueditor", "file", "files", 'color', 'tags', 'markdown', 'city', 'custom'];
        $this->assign('custom', $custom);
        $this->assign('groupArray', config('config_group'));
        $this->modelClass = new ConfigModel;
    }

    //配置首页
    public function index($group = 'base')
    {
        if ($this->request->isAjax()) {
            $_list = $this->modelClass->view('config', 'id,name,title,type,listorder,status,update_time')
                ->where('group', $group)
                ->view('field_type', 'title as ftitle', 'field_type.name=config.type', 'LEFT')
                ->order('listorder,id desc')
                ->select();
            $result = array("code" => 0, "data" => $_list);
            return json($result);
        } else {
            $this->assign('group', $group);
            return $this->fetch();
        }
    }

    //配置设置
    public function setting($group = 'base')
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('modelField/a');
            // 查询该分组下所有的配置项名和类型
            $items = ConfigModel::where('group', $group)->where('status', 1)->column('name,type');
            foreach ($items as $name => $type) {
                //查看是否赋值
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
                //数据格式验证
                if (!empty($data[$name]) && in_array($type, ['number']) && !\think\facade\Validate::isNumber($data[$name])) {
                    return $this->error("'" . $name . "'格式错误~");
                }
                if (isset($data[$name])) {
                    ConfigModel::where(['name' => $name])->setField('value', $data[$name]);
                }
            }
            cache('Config', null);
            return $this->success('设置更新成功');
        } else {
            $configList = ConfigModel::where('group', $group)
                ->where('status', 1)
                ->order('listorder,id desc')
                ->column('name,title,remark,type,value,options');
            foreach ($configList as &$value) {
                $value['fieldArr'] = 'modelField';
                if ($value['type'] == 'custom') {
                    if ($value['options'] != '') {
                        $tpar             = explode(".", $value['options'], 2);
                        $value['options'] = $this->fetch('admin@custom/' . $tpar[0], ['vo' => $value])->getContent();
                        unset($tpar);
                    }
                } elseif ($value['options'] != '') {
                    $value['options'] = parse_attr($value['options']);
                }
                if ($value['type'] == 'checkbox') {
                    $value['value'] = empty($value['value']) ? [] : explode(',', $value['value']);
                }
                if ($value['type'] == 'datetime') {
                    $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : $value['value'];
                }
                if ($value['type'] == 'Ueditor') {
                    $value['value'] = htmlspecialchars_decode($value['value']);
                }
            }
            $this->assign([
                'fieldList' => $configList,
                'group'     => $group,
            ]);
            return $this->fetch();
        }
    }

    //新增配置
    public function add()
    {
        cache('Config', null);
        $groupType = $this->request->param('groupType/s', 'base');
        $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,ifstring');
        $this->assign([
            'fieldType' => $fieldType,
            'groupType' => $groupType,
        ]);
        return parent::add();
    }

    //编辑配置
    public function edit()
    {
        cache('Config', null);
        $id        = $this->request->param('id/d');
        $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,ifstring');
        $this->assign('id', $id);
        $this->assign('fieldType', $fieldType);
        return parent::edit();
    }

    //删除配置
    public function del()
    {
        cache('Config', null); //清空缓存配置
        return parent::del();
    }

    public function multi()
    {
        cache('Config', null); //清空缓存配置
        return parent::multi();
    }

}
