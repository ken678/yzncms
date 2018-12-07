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
// | 数据库管理
// +----------------------------------------------------------------------
namespace addons\database\Controller;

use app\addons\util\Adminaddon;

class Admin extends Adminaddon
{
    protected function initialize()
    {
        parent::initialize();
    }

    //数据库备份
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = db()->query('SHOW TABLE STATUS');
            $list = array_map('array_change_key_case', $list); //全部小写
            $result = array("code" => 0, "data" => $list);
            return json($result);
        }
        return $this->fetch();
    }

    //备份数据库
    public function export()
    {
        var_dump(111);
        exit();

    }

}
