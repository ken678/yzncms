<?php
/**
 * 推荐字段类型表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return string
 */
function posid($field, $value, $fieldinfo)
{
    //扩展配置
    $setting = unserialize($fieldinfo['setting']);
    //推荐位缓存
    $position = cache('Position');
    if (empty($position)) {
        return '';
    }
    $array = array();
    foreach ($position as $_key => $_value) {
        //如果有设置模型，检查是否有该模型
        if ($_value['modelid'] && !in_array($this->modelid, explode(',', $_value['modelid']))) {
            continue;
        }
        //如果设置了模型，又设置了栏目
        if ($_value['modelid'] && $_value['catid'] && !in_array($this->catid, explode(',', $_value['catid']))) {
            continue;
        }
        //如果设置了栏目
        if ($_value['catid'] && !in_array($this->catid, explode(',', $_value['catid']))) {
            continue;
        }
        $array[$_key] = $_value['name'];
    }
    $posids = array();
    if (request()->action() == 'edit') {
        $result = think\Db::name('PositionData')->where(array('id' => $this->id, 'modelid' => $this->modelid))->column("posid,id,catid,posid,module,modelid,thumb,data,listorder,expiration,extention,synedit");
        $posids = implode(',', array_keys($result));
    } else {
        $posids = $setting['defaultvalue'];
    }
    return "<input type='hidden' name='info[{$field}][]' value='-1'>" . \util\Form::checkbox($array, $posids, "name='info[{$field}][]'", '', $setting['width']);
}
