<?php

/**
 * 日期时间字段类型表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function datetime($field, $value, $fieldinfo) {
    //错误提示
    $errortips = $fieldinfo['errortips'];
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : $fieldinfo['name'] . "不能为空！");
    }
    $setting = unserialize($fieldinfo['setting']);
    $isdatetime = 0;
    $timesystem = 0;
    //时间格式
    if ($setting['fieldtype'] == 'int') {//整数 显示格式
        if (empty($value) && $setting['defaulttype']) {
            $value = time();
        }
        //整数 显示格式
        $format_txt = $setting['format'] == 'm-d' ? 'm-d' : $setting['format'];
        if ($setting['format'] == 'Y-m-d Ah:i:s') {
            $format_txt = 'Y-m-d h:i:s';
        }
        $value = $value ? date($format_txt, $value) : '';
        $isdatetime = strlen($setting['format']) > 6 ? 1 : 0;
        if ($setting['format'] == 'Y-m-d Ah:i:s') {
            $timesystem = 0;
        } else {
            $timesystem = 1;
        }
    } elseif ($setting['fieldtype'] == 'datetime') {
        $isdatetime = 1;
        $timesystem = 1;
    } elseif ($setting['fieldtype'] == 'datetime_a') {
        $isdatetime = 1;
        $timesystem = 0;
    }
    return \util\Form::date("info[{$field}]", $value, $isdatetime, 1, 'true', $timesystem);
}
