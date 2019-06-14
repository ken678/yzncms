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
namespace app\common\taglib;

use think\template\TagLib;

class Yzn extends Taglib
{

    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'template' => ['attr' => 'file', 'close' => 0],
        'yzn' => ['attr' => 'module,action,num,cache,type,catid,id,page,msg,blank,return,moreinfo', 'close' => 1, 'level' => 3],
        'get' => ['attr' => 'sql,table,num,cache,page,return', 'close' => 1, 'level' => 3],
    ];

    /**
     * 加载前台模板
     * 格式：{template file="Content/footer.html" theme="主题"/}
     * @staticvar array $_templateParseCache
     * @param type $attr file，theme
     * @param type $content
     * @return string|array 返回模板解析后的内容
     */
    public function tagTemplate($tag, $content)
    {
        $config = cache('Config');
        $theme = isset($tag['theme']) ?: $config['theme'];
        $templateFile = $tag['file'];
        //不是直接指定模板路径的
        if (false === strpos($templateFile, config('template.view_suffix'))) {
            $templateFile = TEMPLATE_PATH . $theme . '/' . $templateFile . config('template.view_suffix');
        } else {
            $templateFile = TEMPLATE_PATH . $theme . '/' . $templateFile;
        }
        //判断模板是否存在
        if (!file_exists($templateFile)) {
            $templateFile = str_replace($theme . '/', 'default/', $templateFile);
            if (!file_exists($templateFile)) {
                return '';
            }
        }
        //读取内容
        $tmplContent = file_get_contents($templateFile);
        //解析模板
        $this->tpl->parse($tmplContent);
        return $tmplContent;
    }

    /**
     * yzn标签
     */
    public function tagYzn($tag, $content)
    {
        //缓存时间
        $cache = isset($tag['cache']) && intval($tag['cache']) ? intval($tag['cache']) : 0;
        //数据返回变量
        $return = isset($tag['return']) && trim($tag['return']) ? trim($tag['return']) : 'data';
        //每页显示总数
        $num = isset($tag['num']) && intval($tag['num']) > 0 ? intval($tag['num']) : 20;
        //模块
        $module = $tag['module'] = strtolower($tag['module']);
        //方法
        $action = $tag['action'] = trim($tag['action']);
        //当前分页参数
        $page = $tag['page'] = (isset($tag['page'])) ? ((substr($tag['page'], 0, 1) == '$') ? $tag['page'] : (int) $tag['page']) : 0;

        //拼接php代码
        $parseStr = '<?php ';
        $parseStr .= '$cache = ' . $cache . ';';
        $parseStr .= '$cacheID = to_guid_string(' . self::arr_to_html($tag) . ');';
        $parseStr .= 'if($cache && $_return = Cache::get($cacheID)):';
        $parseStr .= '$' . $return . ' = $_return;';
        $parseStr .= 'else: ';
        $parseStr .= '$' . $module . 'TagLib =  \think\Container::get("\\\\app\\\\' . $module . '\\\\taglib\\\\' . ucwords($module) . 'TagLib");';
        $parseStr .= 'if(method_exists($' . $module . 'TagLib, "' . $action . '")):';
        $parseStr .= '$' . $return . ' = $' . $module . 'TagLib->' . $action . '(' . self::arr_to_html($tag) . ');';
        $parseStr .= 'if($cache):';
        $parseStr .= 'Cache::set($cacheID, $' . $return . ', $cache);';
        $parseStr .= 'endif;';
        $parseStr .= 'endif;';
        $parseStr .= 'endif;';
        //判断分页
        if ($page) {
            $parseStr .= '$pages = $' . $return . '->render();';
            $parseStr .= '$' . $return . ' = $' . $return . '->items();';
        }
        $parseStr .= ' ?>';
        $parseStr .= $content;
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;
    }

    /**
     * 万能标签
     */
    public function tagGet($tag, $content)
    {
        //缓存时间
        $cache = isset($tag['cache']) && intval($tag['cache']) ? intval($tag['cache']) : 0;
        //数据返回变量
        $return = isset($tag['return']) && trim($tag['return']) ? trim($tag['return']) : 'data';
        //每页显示总数
        $num = isset($tag['num']) && intval($tag['num']) > 0 ? intval($tag['num']) : 20;
        //当前分页参数
        $page = $tag['page'] = (isset($tag['page'])) ? ((substr($tag['page'], 0, 1) == '$') ? $tag['page'] : (int) $tag['page']) : 0;
        //SQL语句
        if (isset($tag['sql'])) {
            $tag['sql'] = $sql = str_replace(array("think_", "yzn_"), config('database.prefix'), strtolower($tag['sql']));
        }
        //表名
        if (isset($tag['table'])) {
            $table = str_replace(config('database.prefix'), '', $tag['table']);
        }
        if (!isset($sql) && !isset($table)) {
            return false;
        }
        //删除，插入不执行！这样处理感觉有点鲁莽了，，，-__,-!
        if (isset($sql) && (strpos($sql, "delete")) === false || isset($sql) && (strpos($sql, "insert")) === false || isset($sql) && (strpos($sql, "update")) === false) {
            return false;
        }
        //如果使用table参数方式，使用类似tp的查询语言效果
        if (isset($table) && $table) {
            $table = strtolower($table);
            //条件
            $tableWhere = array();
            foreach ($tag as $key => $val) {
                if (!in_array($key, explode(',', $this->tags['get']['attr']))) {
                    $tableWhere[$key] = $val;
                }
            }
            if (isset($tag['where'])) {
                array_push($tableWhere, $tag['where']);
            }
        }
        //拼接php代码
        $parseStr = '<?php ';
        $parseStr .= '$cache = ' . $cache . ';';
        if ($table) {
            $parseStr .= '$cacheID = to_guid_string(' . self::arr_to_html($tableWhere) . ');';
            $parseStr .= 'if($cache && $_return = Cache::get($cacheID)):';
            $parseStr .= '$' . $return . ' = $_return;';
            $parseStr .= 'else: ';
            $parseStr .= '$get_db = \think\Db::name(ucwords("' . $table . '"));';
            if (isset($tag['order'])) {
                $parseStr .= ' $get_db->order("' . $tag['order'] . '"); ';
            }
            $parseStr .= '$' . $return . '=$get_db->where(' . self::arr_to_html($tableWhere) . ')->limit(' . $num . ')->select();';
            $parseStr .= 'endif;';
        } else {
            $parseStr .= '$cacheID = to_guid_string(' . self::arr_to_html($tag) . ');';
            $parseStr .= 'if($cache && $_return = Cache::get($cacheID)):';
            $parseStr .= '$' . $return . ' = $_return;';
            $parseStr .= 'else: ';
            //判断是否变量传递
            if (substr(trim($sql), 0, 1) == '$') {
                $parseStr .= ' $_sql = str_replace(array("think_", "yzn_"), config("database.prefix"),' . $sql . ');';
            } else {
                $parseStr .= ' $_sql = "' . str_replace('"', '\"', $sql) . '";';
            }
            $parseStr .= '$' . $return . '=\think\Db::query($_sql." LIMIT ' . $num . ' ");';
            $parseStr .= 'endif;';

        }
        $parseStr .= 'if($cache):';
        $parseStr .= 'Cache::set($cacheID, $' . $return . ', $cache);';
        $parseStr .= 'endif;';
        $parseStr .= ' ?>';
        $parseStr .= $content;
        if (!empty($parseStr)) {
            return $parseStr;
        }
        return;

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
