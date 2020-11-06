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
// | 随机生成类
// +----------------------------------------------------------------------
namespace util;

class Random
{

    /**
     * 生成数字和字母含大小写
     *
     * @param int $len 长度
     * @param string $addChars 额外
     * @return string
     */
    public static function alnum($len = 6, $addChars = '')
    {
        return self::build('alnum', $len, $addChars);
    }

    /**
     * 生成字母含大小写
     *
     * @param int $len 长度
     * @param string $addChars 额外
     * @return string
     */
    public static function alpha($len = 6, $addChars = '')
    {
        return self::build('alpha', $len, $addChars);
    }

    /**
     * 生成指定长度的随机数字
     *
     * @param int $len 长度
     * @param string $addChars 额外
     * @return string
     */
    public static function numeric($len = 4, $addChars = '')
    {
        return self::build('numeric', $len, $addChars);
    }

    /**
     * 生成指定长度的随机汉字
     *
     * @param int $len 长度
     * @param string $addChars 额外
     * @return string
     */
    public static function chinese($len = 4, $addChars = '')
    {
        return self::build('chinese', $len, $addChars);
    }

    /**
     * 能用的随机数生成
     * @param string $type 类型 alpha/alnum/numeric/chinese/unique/md5/encrypt/sha1
     * @param int    $len  长度
     * @param string $addChars 额外
     * @return string
     */
    public static function build($type = 'alnum', $len = 8, $addChars = '')
    {
        switch ($type) {
            case 'alpha':
            case 'alnum':
            case 'numeric':
            case 'chinese':
                switch ($type) {
                    case 'alpha':
                        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                        break;
                    case 'alnum':
                        $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                        break;
                    case 'numeric':
                        $chars = str_repeat('0123456789', 3);
                        break;
                    case 'chinese':
                        $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书" . $addChars;
                        break;
                }
                if ($len > 10) {
                    $chars = $type == 'numeric' ? str_repeat($chars, $len) : str_repeat($chars, 5);
                }
                if ($type != 'chinese') {
                    $chars = str_shuffle($chars);
                    $str   = substr($chars, 0, $len);
                } else {
                    for ($i = 0; $i < $len; $i++) {
                        $str .= mb_substr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
                    }
                }
                return $str;
            case 'unique':
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt':
            case 'sha1':
                return sha1(uniqid(mt_rand(), true));
        }
    }

    /**
     * 根据数组元素的概率获得键名
     *
     * @param array $ps     array('p1'=>20, 'p2'=>30, 'p3'=>50);
     * @param int   $num    默认为1,即随机出来的数量
     * @param bool  $unique 默认为true,即当num>1时,随机出的数量是否唯一
     * @return mixed 当num为1时返回键名,反之返回一维数组
     */
    public static function lottery($ps, $num = 1, $unique = true)
    {
        if (!$ps) {
            return $num == 1 ? '' : [];
        }
        if ($num >= count($ps) && $unique) {
            $res = array_keys($ps);
            return $num == 1 ? $res[0] : $res;
        }
        $max_exp = 0;
        $res     = [];
        foreach ($ps as $key => $value) {
            $value = substr($value, 0, stripos($value, ".") + 6);
            $exp   = strlen(strchr($value, '.')) - 1;
            if ($exp > $max_exp) {
                $max_exp = $exp;
            }
        }
        $pow_exp = pow(10, $max_exp);
        if ($pow_exp > 1) {
            reset($ps);
            foreach ($ps as $key => $value) {
                $ps[$key] = $value * $pow_exp;
            }
        }
        $pro_sum = array_sum($ps);
        if ($pro_sum < 1) {
            return $num == 1 ? '' : [];
        }
        for ($i = 0; $i < $num; $i++) {
            $rand_num = mt_rand(1, $pro_sum);
            reset($ps);
            foreach ($ps as $key => $value) {
                if ($rand_num <= $value) {
                    break;
                } else {
                    $rand_num -= $value;
                }
            }
            if ($num == 1) {
                $res = $key;
                break;
            } else {
                $res[$i] = $key;
            }
            if ($unique) {
                $pro_sum -= $value;
                unset($ps[$key]);
            }
        }
        return $res;
    }

    /**
     * 获取全球唯一标识
     * @return string
     */
    public static function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
