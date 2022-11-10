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
// | 会员模型
// +----------------------------------------------------------------------
namespace app\member\model;

use think\Model;

class Member extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;
    protected $createTime         = 'reg_time';
    protected $insert             = ['reg_ip', 'last_login_ip', 'last_login_time'];
    // 追加属性
    protected $append = [
        'groupname',
    ];

    protected function setRegIpAttr()
    {
        return request()->ip();
    }

    protected function setLastLoginIpAttr()
    {
        return request()->ip();
    }

    protected function setLastLoginTimeAttr()
    {
        return time();
    }

    public function getLastLoginTimeAttr($value, $data)
    {
        return time_format($data['last_login_time']);
    }

    public function getGroupnameAttr($value, $data)
    {
        $group = cache("Member_Group");
        return isset($group[$data['groupid']]['name']) ? $group[$data['groupid']]['name'] : '';
    }

    /**
     * 获取头像
     * @param $value
     * @param $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            $value = config('public_url') . 'static/addons/member/img/avatar.png';
            //启用首字母头像，请使用
            //$value = letter_avatar($data['nickname']);
        }
        return $value;
    }
}
