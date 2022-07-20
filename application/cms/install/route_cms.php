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
    Route::rule('lists/:catid', 'cms/index/lists')->pattern(['catid' => '\d+']);
    Route::rule('shows/:catid/:id', 'cms/index/shows')->pattern(['catid' => '\d+', 'id' => '\d+']);
    Route::rule('tag/[:tag]', 'cms/index/tags');
    Route::rule('search', 'cms/index/search');
    if (isset(cache("Cms_Config")['site_url_mode']) && 2 == cache("Cms_Config")['site_url_mode']) {
        //Route::rule('admin', 'admin/index/login');//如去除c/ d/ 需要解开此注释
        Route::rule('d/:catdir/:id', 'cms/index/shows')->pattern(['catdir' => '[A-Za-z0-9\-\_]+', 'id' => '\d+']);
        Route::rule('c/:catdir/[:condition]', 'cms/index/lists')->pattern(['catdir' => '[A-Za-z0-9\-\_]+', 'condition' => '[0-9_&=a-zA-Z]+']);
    }
});
