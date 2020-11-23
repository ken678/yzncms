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
        $this->banfie     = array("text", "checkbox", "textarea", "radio", "number", "datetime", "image", "images", "array", "switch", "select", "selects", "selectpage", "Ueditor", "file", "files", 'color', 'tags', 'markdown');
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
            $this->assign([
                'groupArray' => config('config_group'),
                'group'      => $group,
            ]);
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
                if (!empty($data[$name]) && in_array($type, ['image', 'number', 'file']) && !\think\facade\Validate::isNumber($data[$name])) {
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
                if ($value['options'] != '') {
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
                $value['fieldArr'] = 'modelField';
            }
            $this->assign([
                'groupArray' => config('config_group'),
                'fieldList'  => $configList,
                'group'      => $group,
            ]);
            return $this->fetch();
        }

    }

    //新增配置
    public function add()
    {
        if ($this->request->isPost()) {
            $data           = $this->request->post();
            $data['status'] = isset($data['status']) ? intval($data['status']) : 1;
            $result         = $this->validate($data, 'Config');
            if (true !== $result) {
                return $this->error($result);
            }
            if (ConfigModel::create($data)) {
                cache('Config', null); //清空缓存配置
                $this->success('配置添加成功~', url('index'));
            } else {
                $this->error('配置添加失败！');
            }
        } else {
            $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,ifstring');
            $this->assign([
                'groupArray' => config('config_group'),
                'fieldType'  => $fieldType,
            ]);
            return $this->fetch();
        }
    }

    //编辑配置
    public function edit()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->post();
            $result = $this->validate($data, 'Config');
            if (true !== $result) {
                return $this->error($result);
            }
            if (ConfigModel::update($data)) {
                cache('Config', null); //清空缓存配置
                $this->success('配置编辑成功~', url('index'));
            } else {
                $this->error('配置编辑失败！');
            }
        } else {
            $id = $this->request->param('id/d');
            if (!is_numeric($id) || $id < 0) {
                return '参数错误';
            }
            $fieldType = Db::name('field_type')->where('name', 'in', $this->banfie)->order('listorder')->column('name,title,ifstring');
            $info      = ConfigModel::get($id);
            $this->assign([
                'groupArray' => config('config_group'),
                'fieldType'  => $fieldType,
                'info'       => $info,
            ]);
            return $this->fetch();
        }
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
