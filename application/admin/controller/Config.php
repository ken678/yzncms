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
    protected $modelValidate      = true;
    protected $modelSceneValidate = true;

    protected function initialize()
    {
        parent::initialize();
        $filepath = APP_PATH . 'admin' . DS . "view" . DS . 'custom' . DS;
        $custom   = str_replace($filepath . DS, '', glob($filepath . DS . 'custom*'));

        $this->assign('custom', $custom);
        $this->assign('groupArray', config('site.config_group'));
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
            $data = $this->request->post('row/a');
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
            try {
                ConfigModel::refreshFile();
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            return $this->success('设置更新成功');
        } else {
            $configList = ConfigModel::where('group', $group)
                ->where('status', 1)
                ->order('listorder,id desc')
                ->column('name,title,remark,type,value,options,visible');
            foreach ($configList as &$value) {
                $value['fieldArr'] = 'row';
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
        $groupType = $this->request->param('groupType', 'base');
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    $result = $this->modelClass->create($params);
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    try {
                        ConfigModel::refreshFile();
                    } catch (Exception $e) {
                        $this->error($e->getMessage());
                    }
                    $this->success('新增成功');
                } else {
                    $this->error($this->modelClass->getError());
                }
            }
            $this->error('参数不能为空');
        }
        $fieldType = Db::name('field_type')->order('listorder')->column('name,title,ifstring');
        $this->assign([
            'fieldType' => $fieldType,
            'groupType' => $groupType,
        ]);
        return $this->fetch();

    }

    //编辑配置
    public function edit()
    {
        $id  = $this->request->param('id/d');
        $row = $this->modelClass->get($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    $result = $row->save($params);
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    try {
                        ConfigModel::refreshFile();
                    } catch (Exception $e) {
                        $this->error($e->getMessage());
                    }
                    $this->success('编辑成功');
                } else {
                    $this->error($row->getError());
                }
            }
            $this->error('参数不能为空');
        }
        $fieldType = Db::name('field_type')->order('listorder')->column('name,title,ifstring');
        $this->assign([
            'data'      => $row,
            'id'        => $id,
            'fieldType' => $fieldType,
        ]);
        return $this->fetch();
    }

    //删除配置
    public function del()
    {
        if (false === $this->request->isPost()) {
            $this->error('未知参数');
        }
        $id  = $this->request->param('id/d');
        $row = ConfigModel::find($id);
        if ($row) {
            try {
                $row->delete();
                ConfigModel::refreshFile();
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success("操作成功！");
        } else {
            $this->error('配置不存在');
        }
    }
}
