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
// | 短信模型
// +----------------------------------------------------------------------
namespace app\common\model;

use \think\Model;

class Sms extends Model
{
    /**
     * 验证码有效时长
     * @var int
     */
    protected $expire = 120;

    /**
     * 最大允许检测的次数
     * @var int
     */
    protected $maxCheckNums = 10;
    /**
     * 校验验证码
     *
     * @param   int    $mobile 手机号
     * @param   int    $code   验证码
     * @param   string $event  事件
     * @return  boolean
     */
    public function check($mobile, $code, $event = 'default')
    {
        $time = time() - $this->expire;
        $sms = self::where(['mobile' => $mobile, 'event' => $event])
            ->order('id', 'DESC')
            ->find();
        if ($sms) {
            if ($sms['create_time'] > $time && $sms['times'] <= $this->maxCheckNums) {
                $correct = $code == $sms['code'];
                if (!$correct) {
                    $sms->times = $sms->times + 1;
                    $sms->save();
                    return false;
                } else {
                    $result = hook('sms_check', $sms);
                    return $result;
                }
            } else {
                // 过期则清空该手机验证码
                $this->flush($mobile, $event);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 清空指定手机号验证码
     *
     * @param   int    $mobile 手机号
     * @param   string $event  事件
     * @return  boolean
     */
    public function flush($mobile, $event = 'default')
    {
        self::where(['mobile' => $mobile, 'event' => $event])
            ->delete();
        hook('sms_flush');
        return true;
    }
}
