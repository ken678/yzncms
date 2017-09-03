<?php
/**
 * 标题字段，表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return string
 */
function title($field, $value, $fieldinfo) {
    //取得标题样式
    $style_arr = explode(';', $this->data['style']);
    //取得标题颜色
    $style_color = $style_arr[0];
    //是否粗体
    $style_font_weight = $style_arr[1] ? $style_arr[1] : '';
    //组合成CSS样式
    $style = 'color:' . $this->data['style'];
    //错误提示
    $errortips = $fieldinfo['errortips'];
    //是否进行最小长度验证
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : "标题不能为空！");
    }
    $str = '<input type="text" style="width:400px;' . ($style_color ? 'color:' . $style_color . ';' : '') . ($style_font_weight ? 'font-weight:' . $style_font_weight . ';' : '') . '" name="info[' . $field . ']" id="' . $field . '" value="' . \util\Input::forTag($value) . '" style="' . $style . '" class="input input_hd J_title_color" placeholder="请输入标题" onkeyup="strlen_verify(this, \'' . $field . '_len\', ' . $fieldinfo['maxlength'] . ')" />
                <input type="hidden" name="style_font_weight" id="style_font_weight" value="' . $style_font_weight . '">';
    //后台的情况下
    if (defined('IN_ADMIN') && IN_ADMIN)
        $str .= '<input type="button" class="btn" id="check_title_alt" value="标题检测" onclick="$.get(\'' . Url::build('Content/Content/public_check_title',array('catid'=>$this->catid)) . '\', {data:$(\'#title\').val()}, function(data){if(data.status==false) {$(\'#check_title_alt\').val(\'标题重复\');$(\'#check_title_alt\').css(\'background-color\',\'#FFCC66\');} else if(data.status==true) {$(\'#check_title_alt\').val(\'标题不重复\');$(\'#check_title_alt\').css(\'background-color\',\'#F8FFE1\')}},\'json\')" style="width:73px;"/>
        <span class="color_pick J_color_pick"><em style="background:' . $style_color . ';" class="J_bg"></em></span>
        <input type="hidden" name="style_color" id="style_color" class="J_hidden_color" value="' . $style_color . '">
        <img src="' . __STATIC__ . '/admin/images/icon/bold.png" width="10" height="10" onclick="input_font_bold()" style="cursor:hand"/>';
    $str .= ' <span>还可输入<B><span id="title_len">' . $fieldinfo['maxlength'] . '</span></B> 个字符</span>';
    return $str;
}