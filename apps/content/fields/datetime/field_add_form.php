<table cellpadding="2" cellspacing="1" >
    <tr> 
        <td><strong>时间格式：</strong></td>
        <td>
            <label><input type="radio" name="setting[fieldtype]" value="date"><span>日期（<?php echo date('Y-m-d'); ?>）</span></label><br />
            <label><input type="radio" name="setting[fieldtype]" value="datetime_a"><span>日期+12小时制时间（<?php echo date('Y-m-d h:i:s'); ?>）</span></label><br />
            <label><input type="radio" name="setting[fieldtype]" value="datetime"><span>日期+24小时制时间（<?php echo date('Y-m-d H:i:s'); ?>）</span></label><br />
            <label><input type="radio" name="setting[fieldtype]" value="int" checked><span>整数 显示格式：</label>
            <select name="setting[format]">
                <option value="Y-m-d Ah:i:s">12小时制:<?php echo date('Y-m-d h:i:s'); ?></option>
                <option value="Y-m-d H:i:s" selected>24小时制:<?php echo date('Y-m-d H:i:s'); ?></option>
                <option value="Y-m-d H:i"><?php echo date('Y-m-d H:i'); ?></option>
                <option value="Y-m-d"><?php echo date('Y-m-d'); ?></option>
                <option value="m-d"><?php echo date('m-d'); ?></option>
            </select></span>
        </td>
    </tr>
    <tr> 
        <td><strong>默认值：</strong></td>
        <td>
            <label><input type="radio" name="setting[defaulttype]" value="0" checked/><span>无</span></label><br />
            <label><input type="radio" name="setting[defaulttype]" value="1" /><span>当前时间</span></label>
        </td>
    </tr>
</table>