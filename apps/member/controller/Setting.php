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
use app\common\model\Module;
use app\member\model\Member as MemberModel;

/**
 * 会员设置
 */
class Setting extends Adminbase
{
    //会员用户组缓存
    protected $groupCache = array();
    //会员模型缓存
    protected $groupsModel = array();
    //会员数据模型
    protected $member = null;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->groupCache = cache("Member_group");
        $this->groupsModel = cache("Model_Member");
        $this->member = new MemberModel;
    }

    //会员模块设置
    public function setting()
    {
        if ($this->request->isPost()) {
            $setting = $this->request->param('setting/a');
            $data['setting'] = serialize($setting);
            $Module = new Module;
            if ($Module->isUpdate(true)->allowField(true)->save($data, ["module" => "member"]) !== false) {
                $this->member->member_cache();
                $this->success("更新成功！", url("Setting/setting"));
            } else {
                $this->error("更新失败！", url("Setting/setting"));
            }
        } else {
            $setting = model("Module")->where(array("module" => "member"))->value("setting");
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
            $this->assign("setting", unserialize($setting));
            return $this->fetch();
        }

    }

}
