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
// | 邮箱模型
// +----------------------------------------------------------------------
namespace app\common\model;

use \think\Model;

class Ems extends Model
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
     * @param   int    $email 邮箱
     * @param   int    $code  验证码
     * @param   string $event 事件
     * @return  boolean
     */
    public function check($email, $code, $event = 'default')
    {
        $time = time() - $this->expire;
        $ems = self::where(['email' => $email, 'event' => $event])
            ->order('id', 'DESC')
            ->find();
        if ($ems) {
            if ($ems['create_time'] > $time && $ems['times'] <= $this->maxCheckNums) {
                $correct = $code == $ems['code'];
                if (!$correct) {
                    $ems->times = $ems->times + 1;
                    $ems->save();
                    return false;
                } else {
                    $result = hook('ems_check', $ems);
                    return true;
                }
            } else {
                // 过期则清空该邮箱验证码
                $this->flush($email, $event);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 清空指定邮箱验证码
     *
     * @param   int    $email 邮箱
     * @param   string $event 事件
     * @return  boolean
     */
    public function flush($email, $event = 'default')
    {
        self::where(['email' => $email, 'event' => $event])
            ->delete();
        hook('ems_flush');
        return true;
    }

}
