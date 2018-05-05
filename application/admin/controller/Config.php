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
// | 系统配置
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;
use think\Db;

class Config extends Adminbase
{
    //配置首页
    public function index()
    {
        $list = Db::view('config', 'id,name,title,type,listorder,status,update_time')
            ->view('field_type', 'title as ftitle', 'field_type.name=config.type', 'LEFT')
            ->order('listorder,id desc')
            ->paginate(15);
        $this->assign([
            'list' => $list,
            'page' => $list->render(),
        ]);
        return $this->fetch();
    }

    //设置配置状态
    public function setstate($id, $status)
    {
        if (($status != 0 && $status != 1) || !is_numeric($id) || $id < 0) {
            return '参数错误';
        }
        if (model('Config')->where('id', $id)->update(['status' => $status])) {
            //cache('system_config', null);
            $this->error('更新成功');
        } else {
            $this->error('更新失败');
        }
    }

}
