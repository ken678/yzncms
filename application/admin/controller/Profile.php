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
// | 个人资料
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Adminlog as AdminlogModel;
use app\admin\model\AdminUser;
use app\common\controller\Adminbase;
use think\facade\Session;
use think\facade\Validate;

class Profile extends Adminbase
{

    public function index()
    {
        if ($this->request->isAjax()) {
            $this->modelClass                      = new AdminlogModel;
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();
            $count                                 = $this->modelClass
                ->where($where)
                ->where('uid', (int) $this->auth->id)
                ->order($sort, $order)
                ->count();
            $list = $this->modelClass
                ->where($where)
                ->where('uid', (int) $this->auth->id)
                ->order($sort, $order)
                ->page($page, $limit)
                ->select();

            $result = ["code" => 0, 'count' => $count, 'data' => $list];
            return json($result);
        }
        return $this->fetch();
    }

    //更新个人信息
    public function update()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post();
            $params = array_filter(array_intersect_key(
                $params,
                array_flip(array('email', 'nickname', 'password', 'avatar'))
            ));
            if (!Validate::isEmail($params['email'])) {
                $this->error('请输入正确的Email地址');
            }
            if (isset($params['password'])) {
                if (!Validate::regex($params['password'], "/^[\S]{6,16}$/")) {
                    $this->error('密码长度必须在6-16位之间，不能包含空格');
                }
                $data               = encrypt_password($params['password']);
                $params['encrypt']  = $data['encrypt'];
                $params['password'] = $data['password'];
            }
            $exist = AdminUser::where('email', $params['email'])->where('id', '<>', (int) $this->auth->id)->find();
            if ($exist) {
                $this->error('邮箱已经存在！');
            }
            if ($params) {
                $admin = AdminUser::get($this->auth->id);
                $admin->save($params);
                //因为个人资料面板读取的Session显示，修改自己资料后同时更新Session
                Session::set("admin", $admin->toArray());
                $this->success('修改成功！');
            }
            $this->error();
        }
        return;
    }
}
