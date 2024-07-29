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
namespace app\admin\controller\general;

use app\common\controller\Backend;
use app\common\model\Config as ConfigModel;
use think\exception\ValidateException;
use think\facade\Db;

class Config extends Backend
{
    protected function initialize()
    {
        parent::initialize();
        $filepath = $this->app->getBasePath() . 'admin' . DS . "view" . DS . 'custom' . DS;
        $custom   = str_replace($filepath . DS, '', glob($filepath . DS . 'custom*'));

        $this->assign('custom', $custom);
        $this->assign('groupArray', config('site.config_group'));
        $this->modelClass = new ConfigModel;
    }

    //配置首页
    public function index($group = 'base')
    {
        if ($this->request->isAjax()) {
            $list = $this->modelClass->view('config')
                ->where('group', $group)
                ->where('config.name', '<>', 'cdnurl')
                ->view('field_type', 'title as ftitle', 'field_type.name=config.type', 'LEFT')
                ->order('listorder desc,id desc')
                ->select();

            return json(["code" => 0, "data" => $list]);
        } else {
            $this->assign('group', $group);
            $this->assignconfig('group', $group);
            return $this->fetch();
        }
    }

    //配置设置
    public function setting($group = 'base')
    {
        if ($this->request->isPost()) {
            $this->token();
            $data = $this->request->post('row/a');
            // 查询该分组下所有的配置项名和类型
            $items = ConfigModel::where('group', $group)->where('status', 1)->column('type', 'name');
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
                    ConfigModel::where(['name' => $name])->update(['value' => $data[$name]]);
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
                ->order('listorder desc,id desc')
                ->select();
            $siteList = [];
            foreach ($configList as $v) {
                $value             = $v->toArray();
                $value['fieldArr'] = 'row';
                if ($value['type'] == 'custom') {
                    if ($value['options'] != '') {
                        $tpar             = explode(".", $value['options'], 2);
                        $value['options'] = $this->fetch('admin@custom/' . $tpar[0], ['vo' => $value]);
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
                if ($value['type'] == 'editor') {
                    $value['value'] = htmlspecialchars_decode($value['value']);
                }
                if ($value['name'] == 'cdnurl') {
                    //cdnurl不支持在线修改
                    continue;
                }
                $siteList[] = $value;
            }
            $this->assign([
                'fieldList' => $siteList,
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
            $this->token();
            $params = $this->request->post("row/a");
            if ($params) {

                try {
                    $this->validate($params, 'app\admin\validate\Config');
                    $res = $this->modelClass->create($params);
                } catch (ValidateException | Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($res !== false) {
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
        $fieldType = Db::name('field_type')->order('listorder')->select();
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
        $row = $this->modelClass->find($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    $this->validate($params, 'app\admin\validate\Config');
                    $res = $row->save($params);
                } catch (ValidateException | Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($res !== false) {
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
        $fieldType = Db::name('field_type')->order('listorder')->select();
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
        $id  = $this->request->param('id/a');
        $row = ConfigModel::find($id[0]);
        if (in_array($row->name, [
            'name',
            'version',
            'config_group',
            'theme', 'admin_forbid_ip',
            'upload_driver',
            'upload_thumb_water',
            'upload_thumb_water_pic',
            'upload_thumb_water_position',
            'upload_thumb_water_alpha',
        ])) {
            $this->error('核心配置禁止删除');
        }
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
