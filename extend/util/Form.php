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
// | 表单构建器
// +----------------------------------------------------------------------
namespace util;

class Form
{
    /**
     * 下拉选择框
     * @param type $array 数据
     * @param type $id 默认选择
     * @param type $str 属性
     * @param type $default_option 默认选项
     * @return boolean|string
     */
    public static function select($array = array(), $id = 0, $str = '', $default_option = '')
    {
        $string = '<select ' . $str . '><option value=""></option>';
        $default_selected = (empty($id) && $default_option) ? 'selected' : '';
        if ($default_option) {
            $string .= '<option value="" $default_selected>$default_option</option>';
        }

        if (!is_array($array) || count($array) == 0) {
            return false;
        }

        $ids = array();
        if (isset($id)) {
            $ids = explode(',', $id);
        }

        foreach ($array as $key => $value) {
            $selected = in_array($key, $ids) ? 'selected' : '';
            $string .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
        $string .= '</select>';
        return $string;
    }

    /**
     * 栏目选择
     * @param string $file 栏目缓存文件名
     * @param intval/array $id 别选中的ID，多选是可以是数组
     * @param string $str 属性
     * @param string $default_option 默认选项
     * @param intval $modelid 按所属模型筛选
     * @param intval $type 栏目类型
     * @param intval $onlysub 只可选择子栏目
     */
    public static function select_category($id = 0, $str = '', $default_option = '', $modelid = 0, $type = -1, $onlysub = 0)
    {
        $tree = new \util\Tree();
        $result = cache('Category');
        $string = '<select ' . $str . '>';
        if ($default_option) {
            $string .= "<option value='0'>{$default_option}</option>";
        }
        $categorys = [];
        if (is_array($result)) {
            foreach ($result as $r) {
                $r = getCategory($r['id']);
                $r['selected'] = '';
                if (is_array($id)) {
                    $r['selected'] = in_array($r['id'], $id) ? 'selected' : '';
                } elseif (is_numeric($id)) {
                    $r['selected'] = $id == $r['id'] ? 'selected' : '';
                }
                $categorys[$r['id']] = $r;
                if ($modelid && $r['modelid'] != $modelid) {
                    unset($categorys[$r['id']]);
                }
            }
        }
        $str = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $str2 = "<optgroup label='\$spacer \$catname'></optgroup>";
        $tree->init($categorys);
        $string .= $tree->get_tree_category(0, $str, $str2);
        $string .= '</select>';
        return $string;

    }

}
