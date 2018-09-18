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

use app\admin\model\AdminUser as AdminUser_model;
use think\Model;

class Adminlog extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * 记录日志
     * @param type $message 说明
     */
    public function record($message, $status = 0)
    {
        $this->AdminUser_model = new AdminUser_model;
        $data = array(
            'uid' => (int) $this->AdminUser_model->isLogin(),
            'status' => $status,
            'info' => "提示语:{$message}",
            'get' => request()->url(),
            'ip' => request()->ip(1),
        );
        return $this->save($data) !== false ? true : false;
    }

}
