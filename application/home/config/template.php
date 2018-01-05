<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------
return [
    // 预先加载的标签库
    'taglib_pre_load' => 'app\\home\\taglib\\Yzn',
    'tpl_replace_string' => [
        '__STATIC__' => '/static',
    ],
    'view_path' => TEMPLATE_PATH . 'default/content/', //TODO：默认前台模板路径
    'PAGE_LISTROWS' => '10',
    'VAR_PAGE' => 'page',
    'PAGE_TEMPLATE' => '<span class="all">共有{recordcount}条信息</span><span class="pageindex">{pageindex}/{pagecount}</span>{first}{prev}{liststart}{list}{listend}{next}{last}',
];
