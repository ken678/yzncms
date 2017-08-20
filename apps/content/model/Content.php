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
namespace app\content\model;
use \think\Model;

/**
 * 菜单模型
 */
class Content extends Model
{

    /**
     * 内容模型处理类生成
     */
    public static function classGenerate() {
        //字段类型存放目录
        $fields_path = APP_PATH . 'content/fields/';
        //内置字段类型列表
        $fields = include $fields_path . 'fields.inc.php';
        $fields = $fields? : array();
        //更新内容模型类：表单生成、入库、更新、输出
        //$classtypes = array('form', 'input', 'output', 'update', 'delete');
        $classtypes = array('form');
        //缓存生成路径
        $cachemodepath = RUNTIME_PATH;
        foreach ($classtypes as $classtype) {
            $content_cache_data = file_get_contents($fields_path . "content_$classtype.php");
            $cache_data = '';
            //循环字段列表，把各个字段的 form.inc.php 文件合并到 缓存 content_form.php 文件
            foreach ($fields as $field => $fieldvalue) {
                //检查文件是否存在
                if (file_exists($fields_path . $field . DIRECTORY_SEPARATOR . $classtype . '.inc.php')) {
                    //读取文件，$classtype.inc.php
                    $ca = file_get_contents($fields_path . $field . DIRECTORY_SEPARATOR . $classtype . '.inc.php');
                    $cache_data .= str_replace(array("<?php", "?>"), "", $ca);
                }
            }
            $content_cache_data = str_replace('##{字段处理函数}##', $cache_data, $content_cache_data);
            //写入缓存
            file_put_contents($cachemodepath . 'content_' . $classtype . '.php', $content_cache_data);
            //设置权限
            chmod($cachemodepath . 'content_' . $classtype . '.php', 0777);
            unset($cache_data);
        }
    }

}