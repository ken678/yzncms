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
// | 友情链接管理
// +----------------------------------------------------------------------
namespace app\admin\controller\links;

use app\admin\model\links\Links as LinksModel;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 友情链接管理
 */
class Admin extends Adminbase
{
    protected $modelValidate = true;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new LinksModel;
    }

    /**
     * 新增友情链接
     */
    public function add()
    {
        $Terms = Db::name('Terms')->where("module", "links")->select();
        $this->assign("Terms", $Terms);
        return parent::add();
    }

    /**
     * 编辑友情链接
     */
    public function edit()
    {
        $Terms = Db::name('Terms')->where("module", "links")->select();
        $this->assign("Terms", $Terms);
        return parent::edit();
    }
}
