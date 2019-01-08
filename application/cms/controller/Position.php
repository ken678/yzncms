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
// | 推荐位管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\common\controller\Adminbase;

class Position extends Adminbase
{
    /**
     * 首页
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = '';
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        return $this->fetch();
    }

}
