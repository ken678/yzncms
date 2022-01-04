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
// | 邮箱验证码类
// +----------------------------------------------------------------------
namespace app\common\library;

use app\common\model\Ems as EmsModel;
use util\Random;

class Ems
{
    /**
     * 验证码有效时长
     * @var int
     */
    protected static $expire = 120;

    /**
     * 最大允许检测的次数
     * @var int
     */
    protected static $maxCheckNums = 10;

    /**
     * 获取最后一次邮箱发送的数据
     *
     * @param   int    $email 邮箱
     * @param   string $event 事件
     * @return  Ems
     */
    public static function get($email, $event = 'default')
    {
        $ems = EmsModel::where(['email' => $email, 'event' => $event])
            ->order('id', 'DESC')
            ->find();
        hook('ems_get', $ems);
        return $ems ? $ems : null;
    }

    /**
     * 发送验证码
     *
     * @param   int    $email 邮箱
     * @param   int    $code  验证码,为空时将自动生成4位数字
     * @param   string $event 事件
     * @return  boolean
     */
    public static function send($email, $code = null, $event = 'default')
    {
        $code   = is_null($code) ? Random::numeric(config('captcha.length')) : $code;
        $time   = time();
        $ems    = EmsModel::create(['event' => $event, 'email' => $email, 'code' => $code, 'create_time' => $time]);
        $result = hook('ems_send', $ems, true, true);
        if (!$result) {
            $ems->delete();
            return false;
        }
        return true;
    }

    /**
     * 发送通知
     *
     * @param   mixed  $email    邮箱,多个以,分隔
     * @param   string $msg      消息内容
     * @param   string $template 消息模板
     * @return  boolean
     */
    public static function notice($email, $msg = '', $template = null)
    {
        $params = [
            'email'    => $email,
            'msg'      => $msg,
            'template' => $template,
        ];
        $result = hook('ems_notice', $params, true, true);
        return $result ? true : false;
    }

    /**
     * 校验验证码
     *
     * @param   int    $email 邮箱
     * @param   int    $code  验证码
     * @param   string $event 事件
     * @return  boolean
     */
    public static function check($email, $code, $event = 'default')
    {
        $time = time() - self::$expire;
        $ems  = EmsModel::where(['email' => $email, 'event' => $event])
            ->order('id', 'DESC')
            ->find();
        if ($ems) {
            if ($ems['create_time'] > $time && $ems['times'] <= self::$maxCheckNums) {
                $correct = $code == $ems['code'];
                if (!$correct) {
                    $ems->times = $ems->times + 1;
                    $ems->save();
                    return false;
                } else {
                    $result = hook('ems_check', $ems, true, true);
                    return $result;
                }
            } else {
                // 过期则清空该邮箱验证码
                self::flush($email, $event);
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
    public static function flush($email, $event = 'default')
    {
        EmsModel::where(['email' => $email, 'event' => $event])->delete();
        hook('ems_flush');
        return true;
    }

}
