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
// | 标签库
// +----------------------------------------------------------------------
namespace addons\links\taglib;

use think\template\TagLib;

class Links extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'list' => ['attr' => 'cache,return,num,typeid,linktype,order,where', 'close' => 1],
    ];

    /**
     * 栏目标签
     */
    public function tagList($tag, $content)
    {
        //缓存时间
        $cache = isset($tag['cache']) && intval($tag['cache']) ? intval($tag['cache']) : 0;
        //数据返回变量
        $return = isset($tag['return']) && trim($tag['return']) ? trim($tag['return']) : 'data';

        $tag['where'] = !empty($tag['where']) ? $this->parseCondition($tag['where']) : '';

        //拼接php代码
        $parseStr = '<?php ';
        $parseStr .= '$' . $return . ' = (new \addons\links\model\Links)->getList(' . self::arr_to_html($tag) . ');';
        $parseStr .= ' ?>';
        $parseStr .= $content;
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 转换数据为HTML代码
     * @param $data 数组
     * @return bool|string
     */
    private static function arr_to_html($data)
    {
        if (is_array($data)) {
            $str = '[';
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $str .= "'$key'=>" . self::arr_to_html($val) . ",";
                } else {
                    //如果是变量的情况
                    if (is_int($val)) {
                        $str .= "'$key'=>$val,";
                    } else if (strpos($val, '$') === 0) {
                        $str .= "'$key'=>$val,";
                    } else if (preg_match("/^([a-zA-Z_].*)\(/i", $val, $matches)) {
                        //判断是否使用函数
                        if (function_exists($matches[1])) {
                            $str .= "'$key'=>$val,";
                        } else {
                            $str .= "'$key'=>'" . self::newAddslashes($val) . "',";
                        }
                    } else {
                        $str .= "'$key'=>'" . self::newAddslashes($val) . "',";
                    }
                }
            }
            $str = rtrim($str, ',');
            return $str . ']';
        }
        return false;
    }

    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return array|string
     */
    protected static function newAddslashes($string)
    {
        if (!is_array($string)) {
            return addslashes($string);
        }
        foreach ($string as $key => $val) {
            $string[$key] = self::newAddslashes($val);
        }
        return $string;
    }

}
