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
// | 字段管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\ModelField;
use app\common\controller\Adminbase;
use think\Db;

class Field extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelfield = new ModelField;

    }

    /**
     * 显示字段列表
     */
    public function index()
    {
        $modelid = $this->request->param('id/d', '');
        if (empty($modelid)) {
            $this->error('参数错误！');
        }
        $model = Db::name("Model")->field(true)->where(array("id" => $modelid))->find();
        if (empty($model)) {
            $this->error('该模型不存在！');
        }
        //根据模型读取字段列表
        $banFields = ['id', 'did', 'status', 'uid'];
        $data = $this->modelfield->where('modelid', $modelid)->whereNotIn('name', $banFields)->order('listorder,id')->select()->withAttr('create_time', function ($value, $data) {
            return date('Y-m-d H:i:s', $value);
        });
        if ($this->request->isAjax()) {
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        $this->assign("modelid", $modelid);
        return $this->fetch();
    }

    /**
     * 模型状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $r = $this->modelfield->where((array('id' => $id)))->value('status');
        $status = $r == '1' ? '0' : '1';
        $this->modelfield->where((array('id' => $id)))->update(array('status' => $status));
        $this->success("操作成功！");
    }

}
