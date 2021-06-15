<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: https://github.com/ITzhiwei/getImgSrc
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 获取HTML字符串中的图片地址类
// +----------------------------------------------------------------------
namespace util;

class GetImgSrc
{

    /**
     * 提取HTML文章中的图片地址
     * @param string $data HTML或者文章
     * @param int $num 第 $num 个图片的src，默认为第一张
     * @param string $order 顺取倒取； 默认为 asc ，从正方向计数。 desc 从反方向计数
     * @param string|array $blacklist 图片地址黑名单，排除图片地址中包含该数据的地址；例如 传入 baidu.com  会排除 src="http://www.baidu.com/img/a.png"
     * @param string $model 默认为字符串模式;可取值 string  preg；string模式处理效率高，PHP版本越高速度越快，可比正则快几倍
     * @return false| null | src  当data为空时返回 false ， src不存在时返回 null ，反之返回src
     */
    public static function src($data, $num = 1, $order = 'asc', $blacklist = false, $model = 'string')
    {

        if (isset($data)) {
            if ($model === 'preg') {
                $imgSrc = self::pregModel($data, $num - 1, $order);
            } else {
                $imgSrc = self::strModel($data, $num, $order);
            }
            if ($blacklist === false) {
                return $imgSrc;
            } else {
                if (is_array($blacklist)) {
                    foreach ($blacklist as $value) {
                        if (strpos($imgSrc, $value) !== false) {
                            return self::src($data, $num + 1, $order, $blacklist, $model);
                        };
                    }
                    return $imgSrc;
                } else {
                    if (false === $blacklist || strpos($imgSrc, $blacklist) === false) {
                        return $imgSrc;
                    } else {
                        return self::src($data, $num + 1, $order, $blacklist, $model);
                    }
                }
            }
        } else {
            return false;
        }
    }

    /**
     * 提取HTML文章中的图片地址
     * @param string $data HTML或者文章
     * @param int $startNum 默认为1，从第一张图片开始抽取
     * @param int $length 从 $startNum 开始抽取，共抽取 $length 张；默认为0，为0则抽取到最后
     * @param string $order 顺取倒取； 默认为 asc ，从正方向计数。 desc 从反方向计数
     * @param string|array $blacklist 图片地址黑名单，排除图片地址中包含该数据的地址；例如 传入 img.baidu.com  会排除 src="img.baidu.com/a.png"
     * @param string $model 抽取集合时，默认为正则模式；可选模式：preg  string，当 $length > 3 或者 $length = 0时，强制使用正则模式，因为取的数量大时，正则速度更快。
     * @return array 图片地址的集合数组，若无则返回空数组
     */
    public static function srcList($data, $startNum = 1, $length = 0, $order = 'asc', $blacklist = false, $model = 'preg')
    {
        if ($model === 'preg' || $length > 3 || $length === 0) {
            $imgSrcArr = self::pregModel($data, [$startNum - 1, $length, $blacklist], $order);
        } else {
            $imgSrcArr = [];
            for ($i = $startNum; $i < $startNum + $length; $i++) {
                $imgSrc = self::strModel($data, $i, $order);
                if (is_array($blacklist)) {
                    $blackBool = true;
                    foreach ($blacklist as $k => $v) {
                        if (strpos($imgSrc, $blacklist) !== false) {
                            $blackBool = false;
                        }
                    }
                    if ($blackBool) {
                        $imgSrcArr[] = $imgSrc;
                    } else {
                        $length++;
                    }
                } else {
                    if (false === $blacklist || strpos($imgSrc, $blacklist) === false) {
                        $imgSrcArr[] = $imgSrc;
                    } else {
                        $length++;
                    }
                }
            }
        }
        return $imgSrcArr;
    }

    /**
     * @param $str
     * @param $num
     * @param $order
     * @return bool|string|null
     */
    public static function strModel($str, $num, $order)
    {
        $topStr = null;
        if ($order != 'asc') {
            $funcStr = 'strrpos';
        } else {
            $funcStr = 'strpos';
        }
        for ($i = 1; $i <= $num; $i++) {
            $firstNum = $funcStr($str, '<img');
            if ($firstNum !== false) {
                if ($order != 'asc') {
                    $topStr = $str;
                    $str    = substr($str, 0, $firstNum);
                } else {
                    $str = substr($str, $firstNum + 4);
                }
            } else {
                return null;
            }
        }
        $str       = $order == 'asc' ? $str : $topStr;
        $firstNum1 = $funcStr($str, 'src=');
        $type      = substr($str, $firstNum1 + 4, 1);
        $str2      = substr($str, $firstNum1 + 5);
        if ($type == '\'') {
            $position = strpos($str2, "'");
        } else {
            $position = strpos($str2, '"');
        }
        $imgPath = substr($str2, 0, $position);
        return $imgPath;
    }

    /**
     * @param $str
     * @param $num
     * @param $order
     * @return string|array|null
     */
    public static function pregModel($str, $num, $order)
    {
        preg_match_all("/<img.*>/isU", $str, $ereg);
        $img = $ereg[0];
        if ($order != 'asc') {
            $img = array_reverse($img);
        };
        if (is_array($num)) {
            $startNum  = $num[0];
            $length    = $num[1];
            $blacklist = $num[2];
            $imgSrcArr = [];
            foreach ($img as $key => $value) {
                $imgSrc    = $value;
                $pregModel = '/src=(\'|")(.*)(?:\1)/isU';
                preg_match_all($pregModel, $imgSrc, $img1);
                if (is_array($blacklist)) {
                    $blacklistBool = true;
                    foreach ($blacklist as $v) {
                        if (strpos($img1[2][0], $v) !== false) {
                            $blacklistBool = false;
                        };
                    }
                    if ($blacklistBool) {
                        $imgSrcArr[] = $img1[2][0];
                    };
                } else {
                    if (false === $blacklist || strpos($img1[2][0], $blacklist) === false) {
                        $imgSrcArr[] = $img1[2][0];
                    };
                }
            }
            if ($length > 0) {
                return array_slice($imgSrcArr, $startNum, $length);
            } else {
                return array_slice($imgSrcArr, $startNum);
            }
        } else {
            if (!empty($img[$num])) {
                $imgStr    = $img[$num];
                $pregModel = '/src=(\'|")(.*)(?:\1)/isU';
                preg_match_all($pregModel, $imgStr, $img1);
                return $img1[2][0];
            } else {
                return null;
            }
        }
    }
}
