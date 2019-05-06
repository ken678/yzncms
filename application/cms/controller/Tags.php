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
// | tags管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\Tags as Tags_Model;
use app\common\controller\Adminbase;

class Tags extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Tags = new Tags_Model;
    }

    /**
     * tags列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page = $this->request->param('page/d', 10);
            $_list = $this->Tags->order(['listorder', 'id' => 'desc'])->page($page, $limit)->select();
            foreach ($_list as $k => &$v) {
                $v['url'] = url('cms/index/tags', ['tag' => $v['tag']]);
            }
            unset($v);

            $total = $this->Tags->count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();

    }

    /**
     * tags编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($this->Tags->save($data, ['id' => $data['tagid']]) !== false) {
                if ($data['oldtagsname'] != $data['tag']) {
                    model('TagsContent')->save(['tag' => $data['tag']], ['tag' => $data['oldtagsname']]);
                }
                $this->success('修改成功！');
            }
            $this->success('修改失败！');

        } else {
            $id = $this->request->param('id/d', 0);
            if (empty($id)) {
                $this->error('请指定需要修改的tags！');
            }
            $data = Tags_Model::get($id);
            $this->assign('data', $data);
            return $this->fetch();
        }

    }

    /**
     * tags删除
     */
    public function delete()
    {
        $tagid = $this->request->param('ids/a', null);
        if (!is_array($tagid)) {
            $tagid = array($tagid);
        }
        foreach ($tagid as $tid) {
            $info = $this->Tags->where(array('id' => $tid))->find();
            if (!empty($info)) {
                if ($this->Tags->where(array('tag' => $info['tag']))->delete() !== false) {
                    model('TagsContent')->where(array('tag' => $info['tag']))->delete();
                }
            }
        }
        $this->success("删除成功！");
    }

    /**
     * tags排序
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = $this->Tags->allowField(['listorder'])->isUpdate(true)->save(['id' => $id, 'listorder' => $listorder]);
        if ($rs) {
            $this->success("菜单排序成功！");
        } else {
            $this->error("菜单排序失败！");
        }
    }

}
