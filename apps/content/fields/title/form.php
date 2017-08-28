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

   /*function title($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $style_arr = explode(';',$this->data['style']);
        $style_color = $style_arr[0];
        $style_font_weight = $style_arr[1] ? $style_arr[1] : '';

        $style = 'color:'.$this->data['style'];
        if(!$value) $value = $defaultvalue;
        $errortips = $this->fields[$field]['errortips'];
        $errortips_max = L('title_is_empty');
        if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips_max.'"});';
        $str = '<input type="text" style="width:400px;'.($style_color ? 'color:'.$style_color.';' : '').($style_font_weight ? 'font-weight:'.$style_font_weight.';' : '').'" name="info['.$field.']" id="'.$field.'" value="'.$value.'" style="'.$style.'" class="measure-input " onBlur="$.post(\'api.php?op=get_keywords&number=3&sid=\'+Math.random()*5, {data:$(\'#title\').val()}, function(data){if(data && $(\'#keywords\').val()==\'\') $(\'#keywords\').val(data); })" onkeyup="strlen_verify(this, \'title_len\', '.$maxlength.');"/><input type="hidden" name="style_color" id="style_color" value="'.$style_color.'">
        <input type="hidden" name="style_font_weight" id="style_font_weight" value="'.$style_font_weight.'">';
        if(defined('IN_ADMIN')) $str .= '<input type="button" class="button" id="check_title_alt" value="'.L('check_title','','content').'" onclick="$.get(\'?m=content&c=content&a=public_check_title&catid='.$this->catid.'&sid=\'+Math.random()*5, {data:$(\'#title\').val()}, function(data){if(data==\'1\') {$(\'#check_title_alt\').val(\''.L('title_repeat').'\');$(\'#check_title_alt\').css(\'background-color\',\'#FFCC66\');} else if(data==\'0\') {$(\'#check_title_alt\').val(\''.L('title_not_repeat').'\');$(\'#check_title_alt\').css(\'background-color\',\'#F8FFE1\')}})" style="width:73px;"/><img src="'.IMG_PATH.'icon/colour.png" width="15" height="16" onclick="colorpicker(\''.$field.'_colorpanel\',\'set_title_color\');" style="cursor:hand"/> 
        <img src="'.IMG_PATH.'icon/bold.png" width="10" height="10" onclick="input_font_bold()" style="cursor:hand"/> <span id="'.$field.'_colorpanel" style="position:absolute;" class="colorpanel"></span>';
        $str .= L('can_enter').'<B><span id="title_len">'.$maxlength.'</span></B> '.L('characters');
        return $str;
    }*/