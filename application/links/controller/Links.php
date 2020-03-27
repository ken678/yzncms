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
namespace app\links\controller;

use app\common\controller\Adminbase;
use app\links\model\Links as Links_Model;
use think\Db;

/**
 * 友情链接管理
 */
class Links extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Links_Model = new Links_Model;
    }

    /**
     * 友情链接列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page = $this->request->param('page/d', 1);
            $_list = $this->Links_Model->order('id', 'desc')->page($page, $limit)->select();
            $total = $this->Links_Model->order('id', 'desc')->count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 新增友情链接
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //验证器
            $rule = [
                'name|网站名称' => 'require',
                'url|网站链接' => 'require|url',
            ];
            $validate = new \think\Validate($rule);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            if (!empty($data['terms']['name'])) {
                $data['termsid'] = $this->addTerms($data['terms']['name']);
            }
            $status = $this->Links_Model->allowField(true)->save($data);
            if ($status) {
                $this->success("添加成功！", url("links/index"));
            } else {
                $this->error("添加失败！");
            }
        } else {
            $Terms = Db::name('Terms')->where(["module" => "links"])->select();
            $this->assign("Terms", $Terms);
            return $this->fetch();
        }
    }

    /**
     * 编辑友情链接
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //验证器
            $rule = [
                'name' => 'require',
                'url' => 'require|url',
            ];
            $msg = [
                'name.require' => '网站名称不得为空',
                'url.require' => '网站链接不得为空',
                'url.url' => '网站链接不是有效URL',
            ];
            $validate = new \think\Validate($rule, $msg);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            if (!empty($data['terms']['name'])) {
                $data['termsid'] = $this->addTerms($data['terms']['name']);
            }
            $status = $this->Links_Model->allowField(true)->save($data, ['id' => $data['id']]);
            if ($status) {
                $this->success("编辑成功！", url("links/index"));
            } else {
                $this->error("编辑失败！");
            }

        } else {
            $id = $this->request->param('id', 0);
            $data = $this->Links_Model->where(array("id" => $id))->find();
            if (!$data) {
                $this->error("该信息不存在！");
            }
            $Terms = Db::name('Terms')->where(["module" => "links"])->select();
            $this->assign("Terms", $Terms);
            $this->assign("data", $data);
            return $this->fetch();
        }

    }

    /**
     * 删除友情链接
     */
    public function delete($ids = 0)
    {
        empty($ids) && $this->error('参数错误！');
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $res = $this->Links_Model->where('id', 'in', $ids)->delete();
        if ($res !== false) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }

    }

    /**
     * 友情链接状态
     */
    public function setstate()
    {
        $id = $this->request->param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $status = $this->request->param('status/d');
        if ($this->Links_Model->where('id', $id)->setField(['status' => $status])) {
            $this->success("操作成功！");
        } else {
            $this->error('操作失败！');
        }
    }

    /**
     * 友情链接排序
     */
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = $this->Links_Model->where(['id' => $id])->setField(['listorder' => $listorder]);
        if ($rs !== false) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序失败！");
        }
    }

    //分类管理
    public function terms()
    {
        if ($this->request->isAjax()) {
            $_list = Db::name('Terms')->where(["module" => "links"])->select();
            $total = count($_list);
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    //分类编辑
    public function termsedit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            if (Db::name('Terms')->update($data) !== false) {
                $this->success("更新成功！", url("links/links/terms"));
            } else {
                $this->error("更新失败！");
            }
        } else {
            $id = $this->request->param('id/d', 0);
            $info = Db::name('Terms')->where(["id" => $id, "module" => "links"])->find();
            if (!$info) {
                $this->error("该分类不存在！");
            }
            $this->assign('data', $info);
            return $this->fetch();
        }
    }

    /**
     * 添加分类
     * @param type $name
     */
    protected function addTerms($name)
    {
        $name = trim($name);
        if (empty($name)) {
            $this->error("分类名称不能为空！");
        }
        $count = Db::name('Terms')->where(["name" => $name, "module" => "links"])->count();
        if ($count > 0) {
            $this->error("该分类已经存在！");
        }
        $TermsId = Db::name('Terms')->insertGetId(["name" => $name, "module" => "links"]);
        if ($TermsId) {
            return $TermsId;
        } else {
            $this->error("分类添加失败！");
        }
    }

    //分类删除
    public function termsdelete()
    {
        $id = $this->request->param('id/d', 0);
        if (Db::name('Terms')->where(["id" => $id, "module" => "links"])->delete()) {
            Db::name('Links')->where(["termsid" => $id])->delete();
            $this->success("分类删除成功！");
        } else {
            $this->error("分类删除失败！");
        }
    }

}
