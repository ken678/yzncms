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
namespace app\admin\controller;

use app\common\controller\Adminbase;

/**
 * 行为日记管理
 */
class Action extends Adminbase
{

    /**
     * 行为日志列表
     */
    public function actionLog()
    {
        $list = $this->lists('ActionLog', array(), 'id desc');
        $this->assign('_list', $list);
        return $this->fetch();
    }

    /**
     * 删除日志
     */
    public function remove($ids = 0)
    {
        empty($ids) && $this->error('参数错误！');
        if (is_array($ids)) {
            $map['id'] = array('in', $ids);
        } elseif (is_numeric($ids)) {
            $map['id'] = $ids;
        }
        $res = db('ActionLog')->where($map)->delete();
        if ($res !== false) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    //删除一个月前记录
    public function clear()
    {
        $res = db('ActionLog')->where(array("create_time" => array("lt", time() - (86400 * 30))))->delete();
        if ($res !== false) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

}
