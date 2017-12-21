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
namespace app\announce\controller;

use app\common\controller\Adminbase;
use think\Db;

/**
 * 系统公告管理
 * @author 御宅男  <530765310@qq.com>
 */
class Announce extends Adminbase
{
    /**
     * [系统公告列表]
     * @author 御宅男  <530765310@qq.com>
     */
    public function index()
    {
        $list = Db::name('Announce')->where('passed', 1)->where('endtime', ['>=', date('Y-m-d')], ['=', '0000-00-00'], 'or')->order(['aid' => 'desc'])->paginate(10);
        $page = $list->render();
        $this->assign('_page', $page);
        $this->assign("list", $list);
        return $this->fetch();
    }

    /**
     * [新增系统公告]
     * @author 御宅男  <530765310@qq.com>
     */
    public function add()
    {
        return $this->fetch();

    }

    /**
     * [编辑系统公告]
     * @author 御宅男  <530765310@qq.com>
     */
    public function edit()
    {

    }

    /**
     * [删除系统公告]
     * @param  integer $ids [description]
     * @author 御宅男  <530765310@qq.com>
     */
    public function delete($ids = 0)
    {
        empty($ids) && $this->error('参数错误！');
        if (is_array($ids)) {
            $map['aid'] = array('in', $ids);
        } elseif (is_numeric($ids)) {
            $map['aid'] = $ids;
        }
        $res = Db::name('Announce')->where($map)->delete();
        if ($res !== false) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

}
