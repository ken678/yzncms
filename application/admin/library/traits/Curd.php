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

use Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

trait Curd
{
    /**
     * 排除前台提交过来的字段
     * @param $params
     * @return array
     */
    protected function preExcludeFields($params)
    {
        if (is_array($this->excludeFields)) {
            foreach ($this->excludeFields as $field) {
                if (key_exists($field, $params)) {
                    unset($params[$field]);
                }
            }
        } else {
            if (key_exists($this->excludeFields, $params)) {
                unset($params[$this->excludeFields]);
            }
        }
        return $params;
    }

    //查看
    public function index()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();

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

    //回收站
    public function recyclebin()
    {
        if ($this->request->isAjax()) {
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();

            $count = $this->modelClass
                ->onlyTrashed()
                ->where($where)
                ->order($sort, $order)
                ->count();

            $data = $this->modelClass
                ->onlyTrashed()
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
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = str_replace("\\model\\", "\\validate\\", get_class($this->modelClass));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->validateFailException(true)->validate($params, $validate);
                    }
                    $result = $this->modelClass->allowField(true)->save($params);
                } catch (ValidateException | PDOException | Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success('新增成功');
                } else {
                    $this->error('未插入任何行');
                }
            }
            $this->error('参数不能为空');
        }
        return $this->fetch();
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $id  = $this->request->param('id/d', 0);
        $row = $this->modelClass->get($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error('你没有权限访问');
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = str_replace("\\model\\", "\\validate\\", get_class($this->modelClass));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $this->validateFailException(true)->validate($params, $validate);
                    }
                    $result = $row->allowField(true)->save($params);
                } catch (ValidateException | PDOException | Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success('修改成功');
                } else {
                    $this->error('未更新任何行');
                }
            }
            $this->error('参数不能为空');
        }
        $this->view->assign("data", $row);
        return $this->fetch();
    }

    //删除
    public function del()
    {
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = [0 => $ids];
        }
        $pk       = $this->modelClass->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        $where    = [];
        if (is_array($adminIds)) {
            $where[] = [$this->dataLimitField, 'in', $adminIds];
        }
        $where[] = [$pk, 'in', $ids];
        $list    = $this->modelClass->where($where)->select();
        $count   = 0;
        try {
            foreach ($list as $k => $v) {
                $count += $v->delete();
            }
        } catch (PDOException | Exception $e) {
            $this->error($e->getMessage());
        }
        if ($count) {
            $this->success("操作成功！");
        }
        $this->error('没有数据删除！');
    }

    //真实删除
    public function destroy()
    {
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = [0 => $ids];
        }
        $pk       = $this->modelClass->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        $where    = [];
        if (is_array($adminIds)) {
            $where[] = [$this->dataLimitField, 'in', $adminIds];
        }
        $where[] = [$pk, 'in', $ids];
        $count   = 0;
        try {
            $list = $this->modelClass->where($where)->onlyTrashed()->select();
            foreach ($list as $item) {
                $count += $item->delete(true);
            }
        } catch (PDOException | Exception $e) {
            $this->error($e->getMessage());
        }
        if ($count) {
            $this->success("操作成功！");
        }
        $this->error('没有数据删除！');
    }

    //还原
    public function restore()
    {
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = [0 => $ids];
        }
        $pk       = $this->modelClass->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        $where    = [];
        if (is_array($adminIds)) {
            $where[] = [$this->dataLimitField, 'in', $adminIds];
        }
        $where[] = [$pk, 'in', $ids];
        $count   = 0;
        try {
            $list = $this->modelClass->where($where)->onlyTrashed()->select();
            foreach ($list as $item) {
                $count += $item->restore();
            }
        } catch (PDOException | Exception $e) {
            $this->error($e->getMessage());
        }
        if ($count) {
            $this->success("操作成功！");
        }
        $this->error('未更新任何行');
    }

    //批量更新
    public function multi()
    {
        $ids = $this->request->param('id/a', null);
        if (empty($ids)) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = [0 => $ids];
        }
        $value = $this->request->param('value/d', 0);
        if ($this->request->has('param')) {
            $param = $this->request->param('param/s');
            $param = in_array($param, (is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields))) ? $param : '';
            if ($param) {
                $pk       = $this->modelClass->getPk();
                $adminIds = $this->getDataLimitAdminIds();
                $where    = [];
                if (is_array($adminIds)) {
                    $where[] = [$this->dataLimitField, 'in', $adminIds];
                }
                $where[] = [$pk, 'in', $ids];
                $count   = 0;
                try {
                    $list = $this->modelClass->where($where)->select();
                    foreach ($list as $item) {
                        $item->{$param} = $value;
                        $count += $item->save();
                    }
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($count) {
                    $this->success("操作成功！");
                }
                $this->error('未更新任何行');
            } else {
                $this->error('操作不允许！');
            }
        }
        $this->error('Param参数不能为空');
    }

}
