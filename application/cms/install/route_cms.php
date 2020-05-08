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
// | CMS路由
// +----------------------------------------------------------------------
Route::group('/', function () {
    Route::rule('', 'cms/index/index');
    Route::rule('index', 'cms/index/index');
    Route::rule('lists/:catid/[:condition]', 'cms/index/lists')->pattern(['catid' => '\d+', 'condition' => '[0-9_&=a-zA-Z]+']);
    Route::rule('shows/:catid/:id', 'cms/index/shows')->pattern(['catid' => '\d+', 'id' => '\d+']);
    Route::rule('tag/[:tag]', 'cms/index/tags');
    Route::rule('search', 'cms/index/search');
});
