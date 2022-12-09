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

use app\admin\model\links\Terms as TermsModel;
use app\common\controller\Adminbase;

/**
 * 友情链接管理
 */
class Terms extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new TermsModel;
    }

    //分类管理
    public function index()
    {
        if ($this->request->isAjax()) {
            [$page, $limit, $where, $sort, $order] = $this->buildTableParames();

            $count = $this->modelClass
                ->where($where)
                ->where("module", "links")
                ->order($sort, $order)
                ->count();

            $data = $this->modelClass
                ->where($where)
                ->where("module", "links")
                ->order($sort, $order)
                ->page($page, $limit)
                ->select();

            $result = ["code" => 0, 'count' => $count, 'data' => $data];
            return json($result);
        }
        return $this->fetch();
    }
}
