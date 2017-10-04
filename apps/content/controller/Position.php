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
namespace app\content\controller;

use app\common\controller\Adminbase;
use app\content\model\Position as PositionModel;
use think\Db;

/**
 * 推荐位管理
 */
class Position extends Adminbase
{
    //首页
    public function index()
    {
        $data = Db::name('Position')->order(array('listorder' => 'ASC', 'posid' => 'DESC'))->select();
        $this->assign('data', $data);
        return $this->fetch();
    }

    //添加推荐位
    public function add()
    {
        if ($this->request->isPost()) {
            $Position = new PositionModel;
            $data = $this->request->param();
            if ($Position->positionAdd($data['info'])) {
                $this->success("添加成功！<font color=\"#FF0000\">请更新缓存！</font>", url("position/index"));
            } else {
                $this->error($Position->getError());
            }
        } else {
            $Model = cache('Model');
            foreach ($Model as $k => $v) {
                if ($v['type'] == 0) {
                    $modelinfo[$v['modelid']] = $v['name'];
                }
            }
            $this->assign('modelinfo', $modelinfo);
            return $this->fetch();
        }
    }

    //编辑推荐位
    public function edit()
    {
        $Position = new PositionModel;
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if ($Position->positionSave($data['info'])) {
                $this->success("更新成功！<font color=\"#FF0000\">请更新缓存！</font>", url("position/index"));
            } else {
                $this->error($Position->getError());
            }
        } else {
            $posid = $this->request->param('posid/d', 0);
            $data = $Position->where(array('posid' => $posid))->find();
            if (!$data) {
                $this->error('该推荐位不存在！');
            }
            $Model = cache('Model');
            foreach ($Model as $k => $v) {
                if ($v['type'] == 0) {
                    $modelinfo[$v['modelid']] = $v['name'];
                }
            }
            $this->assign('data', $data);
            $this->assign('modelinfo', $modelinfo);
            return $this->fetch();
        }

    }

    //删除推荐位
    public function delete()
    {
        $posid = $this->request->param('posid/d', 0);
        $Position = new PositionModel;
        if ($Position->positionDel($posid)) {
            $this->success('删除成功！<font color=\"#FF0000\">请更新缓存！</font>', url('position/index'));
        } else {
            $this->error($Position->getError() ?: '删除失败');
        }
    }

    //栏目排序
    public function listorder()
    {
        $id = $this->request->param('id/d', 0);
        $listorder = $this->request->param('value/d', 0);
        $rs = Db::name('Position')->where('posid', $id)->update(['listorder' => $listorder]);
        if ($rs) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序失败！");
        }
    }

    //推荐位添加栏目加载
    public function public_category_load()
    {
        $modelid = $this->request->param('modelid/d', '');
        $modelidList = explode(',', $modelid);
        $result = cache('Category');
        if (is_array($result)) {
            $categorys = array();
            foreach ($result as $r) {
                $r = getCategory($r['catid']);
                //过滤非普通栏目信息
                if ($r['type'] != 0) {
                    continue;
                }
                $categorys[$r['catid']] = $r['catname'];
                if ($r['child'] != 0) {
                    unset($categorys[$r['catid']]);
                }
                if (!empty($modelid) && !in_array($r['modelid'], $modelidList)) {
                    unset($categorys[$r['catid']]);
                }
            }
        }
        echo \util\Form::checkbox($categorys, $this->request->param('catid/s', 0), 'name="info[catid][]"', '', 1);
    }

}
