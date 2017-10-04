<?php
/**
 * 推荐位字段类型更新回调
 * @param type $field 字段名
 * @param type $value 字段内容
 */
function posid($field, $value)
{
    if (!empty($value) && is_array($value)) {
        //新增
        if (request()->action() == 'add') {
            $position_data_db = think\Loader::model('content/Position');
            $textcontent = array();
            foreach ($this->fields as $_key => $_value) {
                //判断字段是否入库到推荐位字段
                if ($_value['isposition']) {
                    $textcontent[$_key] = $this->data[$_key];
                }
            }
            //颜色选择为隐藏域 在这里进行取值
            $textcontent['style'] = $_POST['style_color'] ? strip_tags($_POST['style_color']) : '';
            if ($_POST['style_font_weight']) {
                $textcontent['style'] = $textcontent['style'] . ';' . strip_tags($_POST['style_font_weight']);
            }
            $posid = array();
            $catid = $this->data['catid'];
            foreach ($value as $r) {
                if ($r != '-1') {
                    $posid[] = $r;
                }
            }
            $position_data_db->positionUpdate($this->id, $this->modelid, $catid, $posid, $textcontent, 0, 1);
        } else {
            $posids = array();
            $catid = $this->data['catid'];
            $position_data_db = think\Loader::model('Content/Position');
            foreach ($value as $r) {
                if ($r != '-1') {
                    $posids[] = $r;
                }
            }
            $textcontent = array();
            foreach ($this->fields as $_value) {
                $field = $_value['field'];
                if ($_value['isposition']) {
                    $textcontent[$field] = $this->data[$field];
                }
            }
            //颜色选择为隐藏域 在这里进行取值
            $textcontent['style'] = $_POST['style_color'] ? strip_tags($_POST['style_color']) : '';
            if ($_POST['style_font_weight']) {
                $textcontent['style'] = $textcontent['style'] . ';' . strip_tags($_POST['style_font_weight']);
            }
            //颜色选择为隐藏域 在这里进行取值
            $position_data_db->positionUpdate($this->id, $this->modelid, $catid, $posids, $textcontent);
        }
    }
}
