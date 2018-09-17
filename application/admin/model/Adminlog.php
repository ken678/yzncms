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
// | 后台用户管理
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;

class Adminlog extends Model
{

    /**
     * 记录日志
     * @param type $message 说明
     */
    public function record($message, $status = 0)
    {
        $data = array(
            'uid' => 1,
            'status' => $status,
            'info' => "提示语：{$message}",
            'get' => $_SERVER['HTTP_REFERER'],
        );
        return $this->save($data) !== false ? true : false;
    }

}
