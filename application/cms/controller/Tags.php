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
// | tags管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\Tags as Tags_Model;
use app\common\controller\Adminbase;

class Tags extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Tags = new Tags_Model;
    }

    /**
     * tags列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = $this->Tags->select();
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        return $this->fetch();

    }

    /**
     * tags编辑
     */
    public function edit()
    {
        return $this->fetch();
    }

    /**
     * tags删除
     */
    public function delete()
    {

    }

}
