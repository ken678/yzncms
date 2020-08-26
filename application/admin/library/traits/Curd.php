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
// | Trait Curd
// +----------------------------------------------------------------------
namespace app\admin\library\traits;

use think\Db;

trait Curd
{
    //查看
    public function index()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $order = $this->request->param("order/s", "DESC");
            $sort = $this->request->param("sort", !empty($this->modelClass) && $this->modelClass->getPk() ? $this->modelClass->getPk() : 'id');

            $count = $this->modelClass
                ->where($where)
                ->order($sort, $order)
                ->count();

            $data = $this->modelClass
                ->where($where)
                ->order($sort, $order)
                ->page($page, $limit)
                ->select();
            $result = ["code" => 0, 'count' => $count, 'data' => $data];
            return json($result);
        }
        return $this->fetch();
    }

    //添加
    public function add()
    {

    }

    //编辑
    public function edit()
    {

    }

    //删除
    public function del()
    {
        $ids = $this->request->param('ids/a', null);
        if (empty($ids)) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        $pk = $this->modelClass->getPk();
        $list = $this->modelClass->where($pk, 'in', $ids)->select();
        $count = 0;
        try {
            foreach ($list as $k => $v) {
                $count += $v->delete();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        if ($count) {
            $this->success("操作成功！");
        } else {
            $this->error('没有数据删除！');
        }
    }

    //批量更新
    public function multi()
    {
        $id = $this->request->param('id/d', 0);
        $value = $this->request->param('value/d', 0);
        if ($this->request->has('param')) {
            $param = $this->request->param('param/s');
            $param = in_array($param, (is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields))) ? $param : '';
            if ($param) {
                try {
                    $row = $this->modelClass->find($id);
                    if (empty($row)) {
                        $this->error('数据不存在！');
                    }
                    $row->{$param} = $value;
                    $row->save();
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                $this->success("操作成功！");
            } else {
                $this->error('操作不允许！');
            }
        }
        $this->error('Param参数不能为空');
    }

}
