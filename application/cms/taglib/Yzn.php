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
namespace app\cms\taglib;

use think\template\TagLib;

class Yzn extends Taglib
{

    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        //上一篇
        'pre' => ['attr' => 'catid,id,target,msg,field', 'close' => 0],
        //下一篇
        'next' => ['attr' => 'catid,id,target,msg,field', 'close' => 0],
        //导航标签
        'category' => ['attr' => 'catid,cache,num,return,where,order', 'close' => 1, 'level' => 3],
    ];

    public function tagCategory($tag, $content)
    {
        //数据返回变量
        $return = isset($tag['return']) ? "data" : $tag['return'];
        $tag['catid'] = $this->autoBuildVar($tag['catid']);
        //拼接php代码
        $parseStr = '<?php ';
        $parseStr .= '$db = model("Category");';
        $parseStr .= '$' . $return . '= $db->getCategory(' . self::arr_to_html($tag) . ');';
        $parseStr .= ' ?>';
        /*$parseStr = '<?php ';
        $parseStr .= '$cache = ' . $cache . ';';
        $parseStr .= '$cacheID = to_guid_string(' . self::arr_to_html($tag) . ');';
        $parseStr .= 'if($cache && $_return = Cache::get($cacheID)):';
        $parseStr .= '$' . $return . ' = $_return;';
        $parseStr .= 'else: ';
        $parseStr .= '$db = db("Category");';
        //如果条件不为空，进行查库
        if (!empty($where)) {
        $parseStr .= '$' . $return . '= $db->where("' . $where . '")->limit(' . $num . ')->select();';
        $parseStr .= 'if($cache):';
        $parseStr .= 'Cache::set($cacheID, $' . $return . ', $cache);';
        $parseStr .= 'endif;';
        }
        $parseStr .= 'endif; ?>';*/
        $parseStr .= $content;
        return $parseStr;
    }

    /**
     * 转换数据为HTML代码
     * @param array $data 数组
     */
    private static function arr_to_html($data)
    {
        if (is_array($data)) {
            $str = 'array(';
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $str .= "'$key'=>" . self::arr_to_html($val) . ",";
                } else {
                    //如果是变量的情况
                    if (strpos($val, '$') === 0) {
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
            return $str . ')';
        }
        return false;
    }

    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    protected static function newAddslashes($string)
    {
        if (!is_array($string)) {
            return addslashes($string);
        }

        foreach ($string as $key => $val) {
            $string[$key] = $this->newAddslashes($val);
        }

        return $string;
    }

}
