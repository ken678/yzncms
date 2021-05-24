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
// | 采集管理
// +----------------------------------------------------------------------
namespace app\collection\controller;

use app\collection\model\Content as Content_Model;
use app\collection\model\Nodes as Nodes_Model;
use app\collection\model\Program as Program_Model;
use app\common\controller\Adminbase;
use think\Db;

class Node extends Adminbase
{
    protected $Nodes_Model;

    protected function initialize()
    {
        parent::initialize();
        $this->Nodes_Model   = new Nodes_Model;
        $this->Content_Model = new Content_Model;
        $this->Program_Model = new Program_Model;
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $data = $this->Nodes_Model->select();
            return json(["code" => 0, "data" => $data]);
        }
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            try {
                $this->Nodes_Model->addNode($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success('新增成功！', url('index'));
        } else {
            return $this->fetch();
        }
    }

    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            try {
                $this->Nodes_Model->editNode($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success('修改成功！', url('index'));
        } else {
            $id = $this->request->param('id/d', 0);
            if (empty($id)) {
                $this->error('请指定需要修改的采集点！');
            }
            $data = $this->Nodes_Model->where('id', $id)->find();
            $this->assign('data', $data);
            return $this->fetch();
        }

    }

    //网址采集
    public function col_url_list()
    {
        set_time_limit(0);
        @session_start();
        \think\facade\Session::pause();
        $nid                      = $this->request->param('id/d', 0);
        $data                     = $this->Nodes_Model->find($nid);
        $data['customize_config'] = json_decode($data['customize_config'], true);
        $event                    = new \app\collection\library\Collection;
        $event->init($data);
        $urls       = $event->url_list();
        $total_page = count($urls);
        if ($total_page > 0) {
            foreach ($urls as $key => $vo) {
                $url = $event->get_url_lists($vo);
                $event->echo_msg("采集起始页：<a href='{$vo}' target='_blank'>{$vo}</a>", 'green');
                if (is_array($url) && !empty($url)) {
                    foreach ($url as $v) {
                        if (empty($v['url']) || empty($v['title'])) {
                            continue;
                        }
                        //是否采集过
                        if (!Content_Model::where(['url' => $v['url']])->find()) {
                            $html = $event->get_content($v['url']);
                            $event->echo_msg($echo_str . "采集内容页：<a href='{$v['url']}' target='_blank'>{$v['url']}</a>", 'black');
                            Content_Model::create(['nid' => $nid, 'status' => 0, 'url' => $v['url'], 'title' => $v['title'], 'data' => serialize($html)]);
                        }
                    }
                }
            }
            $this->Nodes_Model->update(['lastdate' => time(), 'id' => $nid]);
            $event->echo_msg('网址采集已完成！');
        } else {
            $event->echo_msg('网址采集已完成！');
        }

    }

    //文章列表
    public function publist()
    {
        $this->request->only(['id', 'type', 'limit', 'page']);
        $param   = $this->request->param();
        $where   = [];
        $where[] = ['nid', '=', $param['id']];
        if (isset($param['type']) && !empty($param['type'])) {
            $where[] = ['status', '=', $param['type']];
        }
        if ($this->request->isAjax()) {
            $limit = intval($param['limit']) < 10 ? 10 : $param['limit'];
            $page  = intval($param['page']) < 1 ? 1 : $param['page'];
            $data  = $this->Content_Model
                ->where($where)
                ->page($page, $limit)
                ->order('id', 'desc')
                ->select();
            $total = $this->Content_Model->where($where)->order('id', 'desc')->count();
            return json(["code" => 0, "count" => $total, "data" => $data]);
        }
        $this->assign('param', $param);
        return $this->fetch();
    }

    public function show()
    {
        $id   = $this->request->param('id/d', 0);
        $data = $this->Content_Model->where('id', $id)->value('data');
        $this->assign('data', unserialize($data));
        return $this->fetch();
    }

    //导入文章
    public function import()
    {
        $nid = $this->request->param('id/d', 0);
        if ($this->request->isAjax()) {
            $data = $this->Program_Model->where('nid', $nid)->select();
            return json(["code" => 0, "data" => $data]);
        }
        $this->assign('id', $nid);
        return $this->fetch();
    }

    public function add_program()
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
            $result = $this->Program_Model->save([
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
                    $array[$k]['catidurl'] = url('add_program', ['id' => $nid, 'catid' => $v['id']]);
                }
            }
            $tree->init($array);
            $category = $tree->getTree(0, $str);
            if ($catid) {
                $cat_info   = Db::name('Category')->field('catname,modelid')->where('id', $catid)->find();
                $data       = model('cms/cms')->getFieldList($cat_info['modelid']);
                $node_data  = json_decode($this->Nodes_Model->where('id', $nid)->value('customize_config'), true);
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
            $this->assign("catname", $cat_info['catname']);
            $this->assign("category", $category);
            $this->assign('id', $nid);
            $this->assign('catid', $catid);
            return $this->fetch();
        }
    }

    public function edit_program()
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
            $proObj          = Program_Model::get($pid);
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
            $program = unserialize($this->Program_Model->where('id', $pid)->value('config'));
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
                $node_data = json_decode($this->Nodes_Model->where('id', $nid)->value('customize_config'), true);
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

    //导入文章到模型
    public function import_content()
    {
        $nid               = $this->request->param('id/d', 0);
        $pid               = $this->request->param('pid/d', 0);
        $program           = $this->Program_Model->where('id', $pid)->find();
        $program['config'] = unserialize($program['config']);
        $data              = $this->Content_Model->where([
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
            foreach ($program['config']['modelFieldExt'] as $a => $b) {
                if (isset($program['config']['funcs'][$a])) {
                    $sql['modelFieldExt'][$a] = $this->parseFunction($program['config']['funcs'][$a], $v['data'][$b]);
                } else {
                    $sql['modelFieldExt'][$a] = $v['data'][$b];
                }
            }
            try {
                $cms_model->addModelData($sql['modelField'], $sql['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            //更新状态
            $this->Content_Model->where('id', $v['id'])->update(['status' => 2]);

        }
        $this->success('操作成功！', url('publist', ['type' => 2, 'id' => $nid]));
    }

    public function parseFunction($match, $content)
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

    //采集数据删除
    public function content_del()
    {
        $nid  = $this->request->param('id/d', 0);
        $ids  = $this->request->param('ids/a', null);
        $type = $this->request->param('type/s', '');
        if ($type == "all") {
            $this->Content_Model->where('nid', $nid)->delete();
        } else {
            if (empty($ids) || !$nid) {
                $this->error('参数错误！');
            }
            if (!is_array($ids)) {
                $ids = array(0 => $ids);
            }
            try {
                foreach ($ids as $id) {
                    $this->Content_Model->where(['nid' => $nid, 'id' => $id])->delete();
                }
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
        }
        $this->success('删除成功！');

    }

    //方案删除
    public function import_program_del()
    {
        $pid = $this->request->param('pid/d', 0);
        empty($pid) && $this->error('参数不能为空！');
        if ($this->Program_Model->where('id', $pid)->delete()) {
            $this->success("删除成功！");
        } else {
            $this->error('删除失败！');
        }
    }

    public function del()
    {
        $nodeids = $this->request->param('ids/a', null);
        if (!is_array($nodeids)) {
            $nodeids = array($nodeids);
        }
        foreach ($nodeids as $tid) {
            $this->Nodes_Model->where(array('id' => $tid))->delete();
        }
        $this->success("删除成功！");
    }

}
