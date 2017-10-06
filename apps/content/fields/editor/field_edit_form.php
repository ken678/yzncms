<table cellpadding="2" cellspacing="1" width="98%">
    <tr> 
        <td width="110">后台编辑器样式：</td>
        <td><input type="radio" name="setting[toolbar]" value="basic" <?php if ($setting['toolbar'] == 'basic') echo 'checked'; ?>>简洁型 <input type="radio" name="setting[toolbar]" value="full" <?php if ($setting['toolbar'] == 'full') echo 'checked'; ?>> 标准型 </td>
    </tr>
    <tr> 
        <td>前台编辑器样式：</td>
        <td><input type="radio" name="setting[mbtoolbar]" value="basic" <?php if ($setting['mbtoolbar'] == 'basic') echo 'checked'; ?>> 简洁型 <input type="radio" name="setting[mbtoolbar]" value="full" <?php if ($setting['mbtoolbar'] == 'full') echo 'checked'; ?>> 标准型 </td>
    </tr>
    <tr> 
        <td>默认值：</td>
        <td><textarea name="setting[defaultvalue]" rows="2" cols="20" id="defaultvalue" style="height:100px;width:99%;"><?php echo \Input::forTag($setting['defaultvalue']); ?></textarea></td>
    </tr>
    <tr> 
        <td>是否保存远程图片：</td>
        <td><input type="radio" name="setting[enablesaveimage]" value="1" <?php if ($setting['enablesaveimage'] == 1) echo 'checked'; ?>> 是 <input type="radio" name="setting[enablesaveimage]" value="0"  <?php if ($setting['enablesaveimage'] == 0) echo 'checked'; ?>> 否</td>
    </tr>
    <tr> 
        <td>编辑器默认高度：</td>
        <td><input type="text" name="setting[height]" value="<?php echo $setting['height']; ?>" size="4" class="input"> <span>px</span></td>
    </tr>
    <td>字段类型</td>
    <td>
        <select name="setting[fieldtype]">
            <option value="text" <?php if ($setting['fieldtype'] == 'text') echo 'selected'; ?>>小型字符型(TEXT)</option>
            <option value="mediumtext" <?php if ($setting['fieldtype'] == 'mediumtext') echo 'selected'; ?><?php echo  empty($setting['fieldtype'])?'selected':'' ?>>中型字符型(MEDIUMTEXT)</option>
            <option value="longtext" <?php if ($setting['fieldtype'] == 'longtext') echo 'selected'; ?>>大型字符型(LONGTEXT)</option>
        </select> </span>
    </td>
</table>