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
// | 采集验证
// +----------------------------------------------------------------------
namespace app\admin\validate\collection;

use think\Validate;

class Node extends Validate
{

    //定义验证规则
    protected $rule = [
        'name|采集任务名'         => 'require',
        'urlpage|采集网址'       => 'require',
        'pagesize_start|开始页' => 'number|requireIf:sourcetype,1',
        'pagesize_end|结束页'   => 'number|requireIf:sourcetype,1',
        'par_num|加 ? 页'      => 'number|requireIf:sourcetype,1',
    ];

}
