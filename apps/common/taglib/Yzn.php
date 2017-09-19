<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\common\taglib;

use think\config;
use think\template\TagLib;

/**
 * 文档模型标签库
 */
class Yzn extends TagLib
{
    // 数据库where表达式
    protected $comparison = array(
        '{eq}' => '=',
        '{neq}' => '<>',
        '{elt}' => '<=',
        '{egt}' => '>=',
        '{gt}' => '>',
        '{lt}' => '<',
    );
    /**
     * 定义标签列表
     */
    protected $tags = [
        //内容标签
        'content' => ['attr' => 'action,catid,num,page,pagefun,return,where'],
        //SQL标签
        'get' => ['attr' => 'sql,cache,page,dbsource,return,num,pagetp,pagefun,table,order,where'],
    ];

    /**
     * 内容标签
     */
    public function tagContent($tag, $content)
    {
        //栏目ID
        $tag['catid'] = $catid = $tag['catid'];
        //每页显示总数
        $tag['num'] = $num = (int) $tag['num'];
        //当前分页参数
        $tag['page'] = $page = (isset($tag['page'])) ? ((substr($tag['page'], 0, 1) == '$') ? $tag['page'] : (int) $tag['page']) : 0;
        //分页函数，默认page
        $tag['pagefun'] = $pagefun = empty($tag['pagefun']) ? "page" : trim($tag['pagefun']);
        //数据返回变量
        $tag['return'] = $return = empty($tag['return']) ? "data" : $tag['return'];
        //方法
        $tag['action'] = $action = trim($tag['action']);
        //sql语句的where部分
        if (isset($tag['where']) && $tag['where']) {
            $tag['where'] = $this->parseSqlCondition($tag['where']);
        }
        //拼接php代码
        $parseStr = '<?php';
        $parseStr .= ' $content_tag = new \app\content\taglib\Content;' . "\r\n";

        $parseStr .= ' if(method_exists($content_tag, "' . $action . '")){';
        $parseStr .= ' $' . $return . ' = $content_tag->' . $action . '(' . self::arr_to_html($tag) . ');';
        $parseStr .= ' }';
        $parseStr .= ' ?>';
        $parseStr .= $content;
        return $parseStr;

    }

    /**
     * GET标签
     */
    public function tagGet($tag, $content)
    {
        //数据返回变量
        $tag['return'] = $return = empty($tag['return']) ? "data" : $tag['return'];
        //缓存时间
        $tag['cache'] = $cache = (int) $tag['cache'];
        //每页显示总数
        $tag['num'] = $num = isset($tag['num']) && intval($tag['num']) > 0 ? intval($tag['num']) : 20;
        //SQL语句
        if ($tag['sql']) {
            $tag['sql'] = $this->parseSqlCondition($tag['sql']);
        }
        $tag['sql'] = $sql = str_replace(array("think_", "yzn_"), Config::get('database.prefix'), strtolower($tag['sql']));
        //表名
        $table = str_replace(Config::get('database.prefix'), '', $tag['table']);
        if (!$sql && !$table) {
            return false;
        }
        //删除，插入不执行！这样处理感觉有点鲁莽了，，，-__,-!
        if (strpos($tag['sql'], "delete") || strpos($tag['sql'], "insert")) {
            return false;
        }
        //如果使用table参数方式，使用类似tp的查询语言效果
        if ($table) {
            $table = strtolower($table);
            //条件
            $tableWhere = array();
            foreach ($tag as $key => $val) {
                if (!in_array($key, explode(',', $this->tags['get']['attr']))) {
                    $tableWhere[$key] = $this->parseSqlCondition($val);
                }
            }
            /*if ($tag['where']) {
        $tableWhere['_string'] = $this->parseSqlCondition($tag['where']);
        }*/
        }
        //拼接php代码
        $parseStr = '<?php';

        $parseStr .= ' $cache = ' . $cache . ';';
        if ($table) {
            $parseStr .= ' $cacheID = to_guid_string(' . self::arr_to_html($tableWhere) . ');';
            $parseStr .= ' if( ' . $cache . ' && $_return = cache( $cacheID ) ){ ';
            $parseStr .= '      $' . $return . '=$_return;';
            $parseStr .= ' }else{ ';
            $parseStr .= ' $get_db = think\Db::name(ucwords("' . $table . '"));';
            if ($tag['order']) {
                $parseStr .= ' $get_db->order("' . $tag['order'] . '"); ';
            }
            if ($tag['where']) {
                $parseStr .= ' $get_db->where("' . $tag['where'] . '"); ';
            }
            $parseStr .= '$' . $return . '=$get_db->where(' . self::arr_to_html($tableWhere) . ')->limit(' . $num . ')->select();';
        } else {

        }
        $parseStr .= ' if(' . $cache . '){ cache( $cacheID  ,$' . $return . ',$cache); };';
        $parseStr .= ' } ';
        $parseStr .= ' ?>';
        $parseStr .= $content;
        return $parseStr;

    }

    /**
     * 解析条件表达式
     * @access public
     * @param string $condition 表达式标签内容
     * @return array
     */
    protected function parseSqlCondition($condition)
    {
        $condition = str_ireplace(array_keys($this->comparison), array_values($this->comparison), $condition);
        return $condition;
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
