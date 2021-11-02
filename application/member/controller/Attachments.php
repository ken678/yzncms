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
// | 会员附件类
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\attachment\model\Attachment;

class Attachments extends MemberBase
{

    //附件列表页
    public function select()
    {
        if ($this->request->isAjax()) {
            $page   = $this->request->param('page');
            $limit  = $this->request->param('limit');
            $where  = [];
            $_list  = Attachment::where($where)->page($page, $limit)->order('id', 'desc')->select();
            $total  = Attachment::where($where)->order('id', 'desc')->count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch('/attachment');
    }
}
