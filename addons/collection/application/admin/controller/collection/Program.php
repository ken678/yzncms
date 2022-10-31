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
// | 方案管理
// +----------------------------------------------------------------------
namespace app\admin\controller\collection;

use app\admin\model\collection\Content as ContentModel;
use app\admin\model\collection\Nodes as NodesModel;
use app\admin\model\collection\Program as ProgramModel;
use app\common\controller\Adminbase;
use think\Db;

class Program extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new ProgramModel;
    }

    //导入文章
    public function index()
    {
        $nid = $this->request->param('id/d', 0);
        if ($this->request->isAjax()) {
            $data = $this->modelClass->where('nid', $nid)->select();
            return json(["code" => 0, "data" => $data]);
        }
        $this->assign('id', $nid);
        return $this->fetch();
    }

    //添加方案
    public function add()
    {
        $nid   = $this->request->param('id/d', 0);
        $catid = $this->request->param('catid/d', 0);
        $title = $this->request->param('title/s', '');
        if ($this->request->isPost()) {
            $modelid = Db::name('Category')->where('id', $catid)->value('modelid');
            $config  = [];
            $data    = $this->request->post();
            foreach ($data['node_field'] as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                $config[$data['model_type'][$k]][$data['model_field'][$k]] = $v;
            }
            foreach ($data['funcs'] as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                $config['funcs'][$data['model_field'][$k]] = $v;
            }
            $result = $this->modelClass->save([
                'nid'     => $nid,
                'catid'   => $catid,
                'modelid' => $modelid,
                'title'   => $title,
                'config'  => serialize($config),
            ]);
            if (false !== $result) {
                $this->success("添加成功！", url('import', ['id' => $nid]));
            } else {
                $this->error('添加失败！');
            }

        } else {
            $tree  = new \util\Tree();
            $str   = "<option value=@catidurl @selected @disabled>@spacer @catname</option>";
            $array = Db::name('Category')->order('listorder ASC, id ASC')->column('*', 'id');
            foreach ($array as $k => $v) {
                if ($v['id'] == $catid) {
                    $array[$k]['selected'] = "selected";
                }
                //含子栏目和单页不可以发表
                if ($v['child'] == 1 || $v['type'] == 1) {
                    $array[$k]['disabled'] = "disabled";
                    $array[$k]['catidurl'] = '';
                } else {
                    $array[$k]['disabled'] = "";
                    $array[$k]['catidurl'] = url('add', ['id' => $nid, 'catid' => $v['id']]);
                }
            }
            $tree->init($array);
            $category = $tree->getTree(0, $str);
            $cat_info = [];
            if ($catid) {
                $cat_info   = Db::name('Category')->field('catname,modelid')->where('id', $catid)->find();
                $data       = model('cms/cms')->getFieldList($cat_info['modelid']);
                $node_data  = json_decode(NodesModel::where('id', $nid)->value('customize_config'), true);
                $node_field = [];
                if (is_array($node_data)) {
                    foreach ($node_data as $k => $v) {
                        if (empty($v['name']) || empty($v['title'])) {
                            continue;
                        }
                        $node_field[$v['name']] = $v['title'];
                    }
                }
                $this->assign("node_field", $node_field);
                $this->assign("data", $data);
            }
            $this->assign("catname", $cat_info['catname'] ?? '');
            $this->assign("category", $category);
            $this->assign('id', $nid);
            $this->assign('catid', $catid);
            return $this->fetch();
        }
    }

    //方案编辑
    public function edit()
    {
        $nid   = $this->request->param('id/d', 0);
        $pid   = $this->request->param('pid/d', 0);
        $catid = $this->request->param('catid/d', 0);
        $title = $this->request->param('title/s', '');
        if ($this->request->isPost()) {
            $modelid = Db::name('Category')->where('id', $catid)->value('modelid');
            $config  = [];
            $data    = $this->request->post();
            foreach ($data['node_field'] as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                $config[$data['model_type'][$k]][$data['model_field'][$k]] = $v;
            }
            foreach ($data['funcs'] as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                $config['funcs'][$data['model_field'][$k]] = $v;
            }
            $proObj          = ProgramModel::get($pid);
            $proObj->nid     = $nid;
            $proObj->catid   = $catid;
            $proObj->modelid = $modelid;
            $proObj->title   = $title;
            $proObj->config  = serialize($config);
            if ($proObj->save()) {
                $this->success("修改成功！", url('import', ['id' => $nid]));
            } else {
                $this->error('修改失败！');
            }
        } else {
            $program = unserialize($this->modelClass->where('id', $pid)->value('config'));
            $tree    = new \util\Tree();
            $str     = "<option value=@catidurl @selected @disabled>@spacer @catname</option>";
            $array   = Db::name('Category')->order('listorder ASC, id ASC')->column('*', 'id');
            foreach ($array as $k => $v) {
                if ($v['id'] == $catid) {
                    $array[$k]['selected'] = "selected";
                }
                //含子栏目和单页不可以发表
                if ($v['child'] == 1 || $v['type'] == 1) {
                    $array[$k]['disabled'] = "disabled";
                    $array[$k]['catidurl'] = '';
                } else {
                    $array[$k]['disabled'] = "";
                    $array[$k]['catidurl'] = url('add_program', ['id' => $nid, 'catid' => $v['id']]);
                }
            }
            $tree->init($array);
            $category = $tree->getTree(0, $str);
            if ($catid) {
                $cat_info  = Db::name('Category')->field('catname,modelid')->where('id', $catid)->find();
                $data      = model('cms/cms')->getFieldList($cat_info['modelid']);
                $node_data = json_decode(NodesModel::where('id', $nid)->value('customize_config'), true);
                foreach ($data as $key => $value) {
                    if ($value['fieldArr'] == 'modelField') {
                        if (isset($program['modelField'][$key])) {
                            $data[$key]['value'] = $program['modelField'][$key];
                        }
                    }
                    if ($value['fieldArr'] == 'modelFieldExt') {
                        if (isset($program['modelFieldExt'][$key])) {
                            $data[$key]['value'] = $program['modelFieldExt'][$key];
                        }
                    }
                    $data[$key]['funcs'] = isset($program['funcs'][$key]) ? $program['funcs'][$key] : '';
                }
                $node_field = [];
                if (is_array($node_data)) {
                    foreach ($node_data as $k => $v) {
                        if (empty($v['name']) || empty($v['title'])) {
                            continue;
                        }
                        $node_field[$v['name']] = $v['title'];
                    }
                }
                $this->assign("node_field", $node_field);
                $this->assign("data", $data);
            }
            $this->assign('program', $program);
            $this->assign("catname", $cat_info['catname']);
            $this->assign("category", $category);
            $this->assign('id', $nid);
            $this->assign('catid', $catid);
            return $this->fetch();
        }
    }

    //方案删除
    public function del()
    {
        $pid = $this->request->param('pid/d', 0);
        empty($pid) && $this->error('参数不能为空！');
        if ($this->modelClass->where('id', $pid)->delete()) {
            $this->success("删除成功！");
        } else {
            $this->error('删除失败！');
        }
    }

    //导入文章到模型
    public function import_content()
    {
        $nid               = $this->request->param('id/d', 0);
        $pid               = $this->request->param('pid/d', 0);
        $program           = $this->modelClass->where('id', $pid)->find();
        $program['config'] = unserialize($program['config']);
        $data              = ContentModel::where([
            ['nid', '=', $nid],
            ['status', '=', 1],
        ])->select();

        $cms_model = new \app\cms\model\Cms;
        foreach ($data as $k => $v) {
            $sql['modelField'] = array('catid' => $program['catid'], 'status' => 1);
            $v['data']         = unserialize($v['data']);
            if (!$v['data']) {
                continue;
            }
            foreach ($program['config']['modelField'] as $a => $b) {
                if (isset($program['config']['funcs'][$a])) {
                    $sql['modelField'][$a] = $this->parseFunction($program['config']['funcs'][$a], $v['data'][$b]);
                } else {
                    $sql['modelField'][$a] = $v['data'][$b];
                }
            }
            if (isset($program['config']['modelFieldExt'])) {
                foreach ($program['config']['modelFieldExt'] as $a => $b) {
                    if (isset($program['config']['funcs'][$a])) {
                        $sql['modelFieldExt'][$a] = $this->parseFunction($program['config']['funcs'][$a], $v['data'][$b]);
                    } else {
                        $sql['modelFieldExt'][$a] = $v['data'][$b];
                    }
                }
            }
            try {
                $cms_model->addModelData($sql['modelField'], $sql['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            //更新状态
            ContentModel::where('id', $v['id'])->update(['status' => 2]);

        }
        $this->success('操作成功！', url('publist', ['type' => 2, 'id' => $nid]));
    }

    protected function parseFunction($match, $content)
    {
        $varArray = explode('|', $match);
        $length   = count($varArray);
        for ($i = 0; $i < $length; $i++) {
            $args = explode('=', $varArray[$i], 2);
            $fun  = trim($args[0]);
            if (isset($args[1])) {
                $args[1] = explode(',', $args[1]);
                if (false !== $key = array_search("###", $args[1])) {
                    $args[1][$key] = $content;
                    $content       = call_user_func_array($fun, $args[1]);
                } else {
                    $content = call_user_func_array($fun, array_merge([$content], $args[1]));
                }
            } else {
                if (!empty($args[0])) {
                    $content = $fun($content);
                }
            }
        }
        return $content;
    }
}
