<?php
/**
 * 栏目字段
 * @param type $field 字段名
 * @param type $value 字段值
 * @param type $fieldinfo 该字段的配置信息
 * @return type
 */
function catid($field, $value, $fieldinfo) {
    //值不存在则值等于栏目ID
    if(!$value) $value = $this->catid;
    $publish_str = '';
    if (defined("IN_ADMIN") && IN_ADMIN) {
        $publish_str = "<a href='javascript:;' onclick=\"omnipotent('selectid','" . Url::build("Content/Content/public_othors", array("catid" => $this->catid)) . "','同时发布到其他栏目',1);return false;\" style='color:#B5BFBB'>[同时发布到其他栏目]</a>
            <ul class='three_list cc' id='add_othors_text'></ul>";
    }
    $publish_str = '<input type="hidden" name="info[' . $field . ']" value="' . $value . '"/>' . getCategory($value, 'catname') . $publish_str;
    return $publish_str;
}