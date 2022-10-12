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
            $value = config('public_url') . 'static/modules/member/img/avatar.png';
            //启用首字母头像，请使用
            //$value = letter_avatar($data['nickname']);
        }
        return $value;
    }

    /**
     * 更新用户基本资料
     * @param $username 用户名
     * @param $oldpw 旧密码
     * @param string $newpw 新密码，如不修改为空
     * @param string $email 如不修改为空
     * @param int $ignoreoldpw 是否忽略旧密码
     * @param array $data 其他信息
     * @return bool
     */
    public function userEdit($username, $oldpw, $newpw = '', $email = '', $ignoreoldpw = 0, $data = array())
    {
        //验证旧密码是否正确
        if ($ignoreoldpw == 0) {
            $info = self::where(["username" => $username])->find();
            if (encrypt_password($oldpw, $info['encrypt']) != $info['password']) {
                $this->error = '旧密码错误！';
                return false;
            }
        }
        if ($newpw) {
            $passwordinfo = encrypt_password($newpw);
            $data         = array(
                "password" => $passwordinfo['password'],
                "encrypt"  => $passwordinfo['encrypt'],
            );
        } else {
            unset($data['password'], $data['encrypt']);
        }
        if ($email) {
            $data['email'] = $email;
        } else {
            unset($data['email']);
        }
        if (empty($data)) {
            return true;
        }
        if (self::allowField(true)->save($data, ["username" => $username]) !== false) {
            return true;
        } else {
            $this->error = '用户资料更新失败！';
            return false;
        }
    }

    //会员配置缓存
    public function member_cache()
    {
        $data = unserialize(db('Module')->where(['module' => 'member'])->value('setting'));
        cache("Member_Config", $data);
        return $data;
    }

}
