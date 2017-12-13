<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\common\controller\Adminbase;
use app\user\api\UserApi;
use think\Db;
use think\Loader;

/**
 * 会员管理
 */
class Member extends Adminbase
{
    //会员用户组缓存
    protected $groupCache = array();
    //会员模型
    protected $groupsModel = array();

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->groupCache = cache("Member_group"); //会员模型
        $this->groupsModel = cache("Model_Member"); //会员组
    }

    public function manage()
    {
        $map['status'] = array('egt', 0);
        $list = $this->lists('Member', $map);
        int_to_string($list);
        foreach ($this->groupCache as $g) {
            $groupCache[$g['groupid']] = $g['name'];
        }
        foreach ($this->groupsModel as $m) {
            $groupsModel[$m['modelid']] = $m['name'];
        }
        $this->assign('groupCache', $groupCache);
        $this->assign('groupsModel', $groupsModel);
        $this->assign('_list', $list);
        return $this->fetch();
    }

    //添加会员
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->param();
            //数据验证
            $validate = Loader::validate('member/UcenterMember');
            if (!$validate->scene('add')->check($post)) {
                $this->error($validate->getError());
                return false;
            }
            /* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($post['username'], $post['password'], $post['email'], $post['mobile'], 'admin');
            if (0 < $uid) {
                //注册成功
                $post['uid'] = $uid;
                $post['status'] = 1;
                if (!Db::name('Member')->strict(false)->insert($post)) {
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！', url('manage'));
                }
            } else {
                //注册失败，显示错误信息
                $this->error($uid);
            }
        } else {
            foreach ($this->groupCache as $g) {
                if (in_array($g['groupid'], array(8, 1, 7))) {
                    continue;
                }
                $groupCache[$g['groupid']] = $g['name'];
            }
            foreach ($this->groupsModel as $m) {
                $groupsModel[$m['modelid']] = $m['name'];
            }
            $this->assign('groupCache', $groupCache);
            $this->assign('groupsModel', $groupsModel);
            return $this->fetch();
        }
    }

    //删除会员
    public function delete($ids = 0)
    {
        if (empty($ids)) {
            $this->error("请选择需要删除的会员！");
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        $uidarr = array_map('intval', $ids);
        $User = new UserApi;
        foreach ($uidarr as $uid) {
            //UC会员删除
            if ($User->delete_member($uid)) {
                model('Member')->destroy($uid);
            }
        }
        $this->success("删除成功！");
    }

}
