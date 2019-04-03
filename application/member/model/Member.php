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

use \think\Model;

/**
 * 模型
 */
class Member extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $createTime = 'reg_time';
    protected $insert = ['status' => 1, 'reg_ip'];
    protected function setRegIpAttr()
    {
        return request()->ip(1);
    }

    /**
     * 注册一个新用户
     * @param string $username  用户名
     * @param string $password  密码
     * @param string $email     邮箱
     * @param string $mobile    手机号
     * @param array $extend    扩展参数
     */
    public function userRegister($username, $password, $email = '', $mobile = '', $extend = [])
    {
        $passwordinfo = encrypt_password($password); //对密码进行处理
        $data = array(
            "username" => $username,
            "password" => $passwordinfo['password'],
            "email" => $email,
            "encrypt" => $passwordinfo['encrypt'],
            "amount" => 0,
        );
        $userid = $this->save($data);
        if ($userid) {
            return $this->getAttr('id');
        }
        $this->error = $Member->getError() ?: '注册失败！';
        return false;
    }

    /**
     * 删除用户
     * @param type $uid 用户UID
     * @return boolean
     */
    public function userDelete($uid)
    {
        //删除本地用户数据开始
        if (self::where(["id" => $uid])->delete() !== flase) {
            return true;
        }
        $this->error = '删除会员失败！';
        return false;
    }

    //会员配置缓存
    public function member_cache()
    {
        $data = unserialize(db('Module')->where(['module' => 'member'])->value('setting'));
        cache("Member_Config", $data);
        return $data;
    }

}
