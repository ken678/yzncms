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
// | 会员组管理
// +----------------------------------------------------------------------
namespace app\admin\controller\user;

use app\admin\model\user\User as UserModel;
use app\admin\model\user\UserGroup;
use app\common\controller\Backend;
use think\db\exception\PDOException;
use think\exception\ValidateException;

class Group extends Backend
{
    protected $modelValidate = true;
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new UserGroup;
    }

    /**
     * 会员组列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = $this->modelClass->order(["listorder" => "DESC", "id" => "DESC"])->select();
            foreach ($list as $k => $v) {
                //统计会员总数
                $list[$k]['count'] = UserModel::where("group_id", $v['id'])->count('id');
            }
            $result = ["code" => 0, "data" => $list];
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 会员组编辑
     */
    public function edit()
    {
        $id  = $this->request->param('id/d', 0);
        $row = $this->modelClass->find($id);
        if (!$row) {
            $this->error('记录未找到');
        }
        if ($this->request->isPost()) {
            $params                     = $this->request->post('row/a');
            $params['allowpost']        = $params['allowpost'] ?? 0;
            $params['allowpostverify']  = $params['allowpostverify'] ?? 0;
            $params['allowsendmessage'] = $params['allowsendmessage'] ?? 0;
            $params['allowattachment']  = $params['allowattachment'] ?? 0;
            $params['allowsearch']      = $params['allowsearch'] ?? 0;

            $result = false;
            try {
                //是否采用模型验证
                if ($this->modelValidate) {
                    $name     = str_replace("\\model\\", "\\validate\\", get_class($this->modelClass));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                    $this->validateFailException(true)->validate($params, $validate);
                }
                $result = $row->save($params);
            } catch (ValidateException $e) {
                $this->error($e->getMessage());
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            if ($result !== false) {
                $this->success('修改成功');
            } else {
                $this->error('未更新任何行');
            }
        } else {
            $this->assign("data", $row);
            return $this->fetch();
        }
    }

}
