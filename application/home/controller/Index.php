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
namespace app\home\controller;

use app\common\controller\Homebase;

//use think\Db;
//use think\Loader;

class Index extends Homebase
{

    /**
     * 首页
     */
    public function index()
    {
        $SEO = seo();
        $this->assign("SEO", $SEO);
        return $this->fetch();
    }

}
