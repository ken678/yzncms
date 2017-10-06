<table cellpadding="2" cellspacing="1" width="98%">
    <tr> 
        <td width="120">文本框长度</td>
        <td><input type="text" name="setting[width]" value="<?php echo $setting['width']; ?>" size="10" class="input"> <span>px</span></td>
    </tr>
    <tr> 
        <td>默认值</td>
        <td><input type="text" name="setting[defaultvalue]" value="<?php echo $setting['defaultvalue']; ?>" size="40" class="input"></td>
    </tr>
    <tr> 
        <td>表单显示模式</td>
        <td><input type="radio" name="setting[show_type]" value="1" <?php if ($setting['show_type']) echo 'checked'; ?>/> 图片模式 <input type="radio" name="setting[show_type]" value="0" <?php if (!$setting['show_type']) echo 'checked'; ?>/> 文本框模式</td>
    </tr>
    <tr> 
        <td>允许上传的图片类型</td>
        <td><input type="text" name="setting[upload_allowext]" value="<?php echo $setting['upload_allowext']; ?>" size="40" class="input"></td>
    </tr>
    <tr> 
        <td>是否在图片上添加水印</td>
        <td><input type="radio" name="setting[watermark]" value="1" <?php if ($setting['watermark']) echo 'checked'; ?>> 是 <input type="radio" name="setting[watermark]" value="0" <?php if (!$setting['watermark']) echo 'checked'; ?>> 否</td>
    </tr>
    <tr> 
        <td>是否从已上传中选择</td>
        <td><input type="radio" name="setting[isselectimage]" value="1" <?php if ($setting['isselectimage']) echo 'checked'; ?>> 是 <input type="radio" name="setting[isselectimage]" value="0" <?php if (!$setting['isselectimage']) echo 'checked'; ?>> 否</td>
    </tr>
    <tr> 
        <td>自动裁减图像大小</td>
        <td><span>宽 <input type="text" name="setting[images_width]" value="<?php echo $setting['images_width']; ?>" size="3" class="input"> px 高 <input type="text" name="setting[images_height]" value="<?php echo $setting['images_height']; ?>" size="3" class="input"> px </span></td>
    </tr>
</table>