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
// | cms管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\common\controller\Adminbase;
use think\Db;

class Cms extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        return $this->fetch();
    }

    //栏目信息列表
    public function classlist()
    {
        $catid = $this->request->param('id/d', 0);
        if ($this->request->isAjax()) {
            //当前栏目信息
            $catInfo = getCategory($catid);
            if (empty($catInfo)) {
                $this->error('该栏目不存在！');
            }
            //栏目所属模型
            $modelid = $catInfo['modelid'];
            //检查模型是否被禁用
            if (!getModel($modelid, 'status')) {
                $this->error('模型被禁用！');
            }
            $modelCache = cache("Model");
            $tableName = $modelCache[$modelid]['tablename'];
            $list = Db::name(ucwords($tableName))->select();
        }
        $this->assign('id', $catid);
        return $this->fetch();

    }

    //添加栏目
    public function add()
    {
        $category = getCategory($this->request->param('id/d', 0));
        if (empty($category)) {
            $this->error('该栏目不存在！');
        }
        $modelid = $category['modelid'];
        $fieldList = model('ModelField')->getFieldList($modelid);
        $this->assign([
            'fieldList' => $fieldList,
        ]);
        return $this->fetch();

    }

    public function panl()
    {
    }

    //显示栏目菜单列表
    public function public_categorys()
    {
        $json = [];
        $categorys = cache('Category');
        foreach ($categorys as $rs) {
            $rs = getCategory($rs['id']);
            //外部链接
            if ($rs['type'] == 3 && $rs['child'] == 0) {
                continue;
            }
            $data = array(
                'id' => $rs['id'],
                'parentid' => $rs['parentid'],
                'catname' => $rs['catname'],
                'type' => $rs['type'],
            );

            //终极栏目
            if ($rs['child'] == 0) {
                $data['target'] = 'right';
                $data['url'] = url('cms/cms/classlist', array('id' => $rs['id']));
            } else {
                $data['isParent'] = true;
            }

            //单页
            if ($rs['type'] == 1 && $rs['child'] == 0) {
                $data['url'] = url('cms/cms/add', array('id' => $rs['id']));
            }

            $json[] = $data;
        }
        $this->assign('json', json_encode($json));
        return $this->fetch();
    }

}
