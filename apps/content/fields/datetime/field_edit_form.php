<table cellpadding="2" cellspacing="1" >
    <tr> 
        <td><strong>时间格式：</strong></td>
        <td>
            <label><input type="radio" name="setting[fieldtype]" value="date" <?php if ($setting['fieldtype'] == 'date') echo 'checked'; ?>><span>日期（<?= date('Y-m-d') ?>）</span></label><br />
            <label><input type="radio" name="setting[fieldtype]" value="datetime_a" <?php if ($setting['fieldtype'] == 'datetime_a') echo 'checked'; ?>><span>日期+12小时制时间（<?= date('Y-m-d h:i:s') ?>）</span></label><br />
            <label><input type="radio" name="setting[fieldtype]" value="datetime" <?php if ($setting['fieldtype'] == 'datetime') echo 'checked'; ?>><span>日期+24小时制时间（<?= date('Y-m-d H:i:s') ?>）</span></label><br />
            <label><input type="radio" name="setting[fieldtype]" value="int" <?php if ($setting['fieldtype'] == 'int') echo 'checked'; ?>><span>整数 显示格式：</label>
            <select name="setting[format]">
                <option value="Y-m-d Ah:i:s" <?php if ($setting['format'] == 'Y-m-d Ah:i:s') echo 'selected'; ?>>12小时制:<?php echo date('Y-m-d h:i:s') ?></option>
                <option value="Y-m-d H:i:s" <?php if ($setting['format'] == 'Y-m-d H:i:s') echo 'selected'; ?>>24小时制:<?php echo date('Y-m-d H:i:s') ?></option>
                <option value="Y-m-d H:i" <?php if ($setting['format'] == 'Y-m-d H:i') echo 'selected'; ?>><?php echo date('Y-m-d H:i') ?></option>
                <option value="Y-m-d" <?php if ($setting['format'] == 'Y-m-d') echo 'selected'; ?>><?php echo date('Y-m-d') ?></option>
                <option value="m-d" <?php if ($setting['format'] == 'm-d') echo 'selected'; ?>><?php echo date('m-d') ?></option>
            </select></span>
        </td>
    </tr>
    <tr> 
        <td><strong>默认值：</strong></td>
        <td>
            <label><input type="radio" name="setting[defaulttype]" value="0" <?php if ($setting['defaulttype'] == '0') echo 'checked'; ?>/><span>无</span></label><br />
            <label><input type="radio" name="setting[defaulttype]" value="1" <?php if ($setting['defaulttype'] == '1') echo 'checked'; ?>/><span>当前时间</span></label>
        </td>
    </tr>
</table>