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
class Action extends Adminbase {

    protected function _initialize() {
        $this->assign('__GROUP_MENU__', $this->get_group_menu());
    }

    /**
     * 行为日志列表
     * @author huajie <banhuajie@163.com>
     */
    public function actionLog(){
        return $this->fetch();
    }


    /**
     * 获取xml数据
     */
    public function get_xml(){
        //获取列表数据
        $map=  array();
        $list   =   $this->lists('ActionLog', $map);
        int_to_string($list['data']);
        $data = array();
        $data['now_page'] = $list['current_page'];//当前页
        $data['total_num'] = $list['total'];//总页数
        foreach ($list['data'] as $k => $info) {
            $list = array();
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$info['id']})\"><i class='fa fa-trash-o'></i>删除</a>";
            $list['admin_name'] = get_username($info['user_id']);
            $list['content'] = $info['remark'];
            $list['createtime'] = date('Y-m-d H:i:s',$info['create_time']);
            $list['ip'] = long2ip($info['action_ip']);
            $data['list'][$info['id']] = $list;
        }
        exit(flexigridXML($data));
    }











}