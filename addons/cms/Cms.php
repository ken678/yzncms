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
// | 内容管理插件
// +----------------------------------------------------------------------
namespace addons\cms;

use addons\cms\library\FulltextSearch;
use think\Addons;
use think\facade\Route;

class Cms extends Addons
{
    //安装
    public function install()
    {
        return true;
    }

    //卸载
    public function uninstall()
    {
        return true;
    }

    //或者run方法
    public function userSidenavAfter($content)
    {
        return $this->fetch('userSidenavAfter');
    }

    public function xunsearchIndexReset($project)
    {
        if ($project['name'] == 'cms') {
            return FulltextSearch::reset();
        }
    }

    public function appInit()
    {
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

        //此函数需要全局调用
        if (is_file(ADDON_PATH . 'cms' . DS . 'function.php')) {
            include_once ADDON_PATH . 'cms' . DS . 'function.php';
        }
    }

}
