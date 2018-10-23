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
    protected $autoWriteTimestamp = true;
    /**
     * 记录日志
     * @param type $message 说明
     */
    public function record($message, $status = 0)
    {
        $data = array(
            'uid' => (int) model('admin/AdminUser')->isLogin(),
            'status' => $status,
            'info' => "提示语:{$message}",
            'get' => request()->url(),
            'ip' => request()->ip(1),
        );
        return $this->save($data) !== false ? true : false;
    }

    /**
     * 删除一个月前的日志
     * @return boolean
     */
    public function deleteAMonthago()
    {
        $status = $this->where('create_time', '<= time', time() - (86400 * 30))->delete();
        return $status !== false ? true : false;
    }

}
