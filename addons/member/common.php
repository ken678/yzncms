<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | member函数文件
// +----------------------------------------------------------------------

/**
 * 首字母头像
 * @param $text
 * @return string
 */
function letter_avatar($text)
{
    $total           = unpack('L', hash('adler32', $text, true))[1];
    $hue             = $total % 360;
    list($r, $g, $b) = hsv2rgb($hue / 360, 0.3, 0.9);

    $bg    = "rgb({$r},{$g},{$b})";
    $color = "#ffffff";
    $first = mb_strtoupper(mb_substr($text, 0, 1));
    $src   = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><rect fill="' . $bg . '" x="0" y="0" width="100" height="100"></rect><text x="50" y="50" font-size="50" text-copy="fast" fill="' . $color . '" text-anchor="middle" text-rights="admin" alignment-baseline="central">' . $first . '</text></svg>');
    $value = 'data:image/svg+xml;base64,' . $src;
    return $value;
}
