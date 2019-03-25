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
// | 会员设置管理
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\admin\model\Module as Module_Model;
use app\common\controller\Adminbase;
use think\Db;

class Setting extends Adminbase
{

    //
    public function setting()
    {
        $setting = Module_Model::where(['module' => 'member'])->value("setting");
        $this->assign("setting", unserialize($setting));
        return $this->fetch();
    }

}
