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
// | 数据导出管理
// +----------------------------------------------------------------------
namespace addons\dataoutput\Controller;

use app\addons\util\Adminaddon;
use think\Db;

class Admin extends Adminaddon
{
    public function index()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page = $this->request->param('page/d', 1);
            $data = Db::name('dataoutput')
                ->page($page, $limit)
                ->order('id', 'desc')
                ->select();
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        return $this->fetch();
    }

    public function add()
    {
        $list = Db::query('SHOW TABLE STATUS');
        $list = array_map('array_change_key_case', $list); //全部小写
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function fieldlist()
    {
        $name = $this->request->param('tablename/s', '');
        $data = Db::query("show full columns from {$name}");
        $result = array("code" => 0, "data" => $data);
        return json($result);
    }

}
