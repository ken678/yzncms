<?php

/**
 * 编辑器获取内容
 * @param type $field 字段名
 * @param type $value 字段内容
 * @return type
 */
function editor($field, $value) {
    $setting = unserialize($this->fields[$field]['setting']);
    $isadmin = 0;
    //是否保存远程图片
    $enablesaveimage = (int) $setting['enablesaveimage'];
    if (defined("IN_ADMIN") && IN_ADMIN) {
        $isadmin = 1;
    }
    if ($enablesaveimage) {
        $Attachment = service('Attachment', array(
            "module" => $this->catid ? 'Content' : MODULE_NAME,
            "catid" => $this->catid? : 0,
            "isadmin" => $isadmin,
        ));
        //下载远程图片
        $value = $Attachment->download($value);
    }
    return $value;
}
