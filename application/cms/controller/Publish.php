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
// | 稿件管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\Category as Category_Model;
use app\cms\model\Cms as Cms_Model;
use app\common\controller\Adminbase;
use app\member\model\MemberContent as Member_Content_Model;
use think\Db;

class Publish extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Cms_Model = new Cms_Model;
        //$this->Member_Content_Model = new Member_Content_Model;
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page = $this->request->param('page/d', 10);

            $total = Member_Content_Model::count();
            $_list = Member_Content_Model::page($page, $limit)->order(array("id" => "DESC"))->select();

            foreach ($_list as $k => $v) {
                $modelid = Category_Model::getCategory($v['catid'], 'modelid');
                $tablename = ucwords(getModel($modelid, 'tablename'));
                $info = Db::name($tablename)->where(array("id" => $v['content_id'], "sysadd" => 0))->find();
                if ($info) {
                    $_list[$k]['url'] = $this->Cms_Model->buildContentUrl($v['catid'], $v['content_id']);
                    $_list[$k]['title'] = $info['title'];
                    $_list[$k]['catname'] = Category_Model::getCategory($v['catid'], 'catname');
                }
            }
            $result = array("code" => 0, "count" => $total, "data" => $_list);

            return json($result);
        }
        return $this->fetch();
    }

}
