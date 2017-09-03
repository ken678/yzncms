<?php

/**
 * 图片字段表单组合处理
 * @param type $field 字段名
 * @param type $value 字段内容
 * @param type $fieldinfo 字段配置
 * @return type
 */
function image($field, $value, $fieldinfo) {
    //错误提示
    $errortips = $fieldinfo['errortips'];
    if ($fieldinfo['minlength']) {
        //验证规则
        $this->formValidateRules['info[' . $field . ']'] = array("required" => true);
        //验证不通过提示
        $this->formValidateMessages['info[' . $field . ']'] = array("required" => $errortips ? $errortips : $fieldinfo['name'] . "不能为空！");
    }
    $setting = unserialize($fieldinfo['setting']);
    $width = $setting['width'] ? $setting['width'] : 180;
    $html = '';
    //图片裁减功能只在后台使用
    if (defined('IN_ADMIN') && IN_ADMIN) {
        $html = " <input type=\"button\" class=\"btn\" onclick=\"crop_cut_" . $field . "($('#$field').val());return false;\" value=\"裁减图片\">
            <input type=\"button\"  class=\"btn\" onclick=\"$('#" . $field . "_preview').attr('src','" . __STATIC__ . "/admin/images/icon/upload-pic.png');$('#" . $field . "').val('');return false;\" value=\"取消图片\"><script type=\"text/javascript\">
            function crop_cut_" . $field . "(id){
	if ( id =='' || id == undefined ) {
                      isalert('请先上传缩略图！');
                      return false;
                    }
                    var catid = $('input[name=\"info[catid]\"]').val();
                    if(catid == '' ){
                        isalert('请选择栏目ID！');
                        return false;
                    }
                    Wind.use('artDialog','iframeTools',function(){
                      art.dialog.open('index.php?a=public_imagescrop&m=Content&g=Content&catid='+catid+'&picurl='+encodeURIComponent(id)+'&input=$field&preview=" . ($setting['show_type'] && defined('IN_ADMIN') ? $field . "_preview" : '') . "', {
                        title:'裁减图片',
                        id:'crop',
                        ok: function () {
                            var iframe = this.iframe.contentWindow;
                            if (!iframe.document.body) {
                                 alert('iframe还没加载完毕呢');
                                 return false;
                            }
                            iframe.uploadfile();
                            return false;
                        },
                        cancel: true
                      });
                    });
            };
</script>";
    }
    //模块
    $module = request()->module();
    //生成上传附件验证
    $authkey = upload_key("1,{$setting['upload_allowext']},{$setting['isselectimage']},{$setting['images_width']},{$setting['images_height']},{$setting['watermark']}");
    //图片模式
    if ($setting['show_type']) {
        $preview_img = $value ? $value : __STATIC__ . '/admin/images/icon/upload-pic.png';
        return "<div  style=\"text-align: center;\"><input type='hidden' name='info[$field]' id='$field' value='$value'>
			<a href='javascript:void(0);' onclick=\"flashupload('{$field}_images', '附件上传','{$field}',thumb_images,'1,{$setting['upload_allowext']},{$setting['isselectimage']},{$setting['images_width']},{$setting['images_height']},{$setting['watermark']}','{$module}','$this->catid','$authkey');return false;\">
			<img src='$preview_img' id='{$field}_preview' width='135' height='113' style='cursor:hand' /></a>
                       <br/> " . $html . "
                   </div>";
    } else {
        //文本框模式
        return "<input type='text' name='info[$field]' id='$field' value='$value' style='width:{$width}px;' class='input' />  <input type='button' class='button' onclick=\"flashupload('{$field}_images', '附件上传','{$field}',submit_images,'1,{$setting['upload_allowext']},{$setting['isselectimage']},{$setting['images_width']},{$setting['images_height']},{$setting['watermark']}','{$module}','$this->catid','$authkey')\"/ value='上传图片'>" . $html;
    }
}