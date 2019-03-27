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

    /**
     * 注册一个新用户
     * @param string $username  用户名
     * @param string $password  密码
     * @param string $email     邮箱
     * @param string $mobile    手机号
     * @param array $extend    扩展参数
     */
    public function register($username, $password, $email = '', $mobile = '', $extend = [])
    {
        $data = array(
            "username" => $username,
            "password" => $password,
            "email" => $email,
            "encrypt" => $encrypt,
            "amount" => 0,
        );
        $userid = $this->save($data);
        if ($userid) {
            return $userid;
        }
        $this->error = $Member->getError() ?: '注册失败！';
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
