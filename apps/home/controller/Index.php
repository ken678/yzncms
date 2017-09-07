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

/**
 * 前台
 */
class Index extends Homebase
{

    /**
     * 首页
     */
    public function index()
    {

        return '首页暂无 请进后台 当前后缀加admin';
    }

    /**
     * 列表页
     */
    public function lists()
    {
        return $this->fetch();
    }

    /**
     * 内容页
     */
    public function shows()
    {
        return $this->fetch();
    }

}
