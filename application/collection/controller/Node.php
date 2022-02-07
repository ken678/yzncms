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

use app\collection\model\Content as ContentModel;
use app\collection\model\Nodes as NodesModel;
use app\common\controller\Adminbase;

class Node extends Adminbase
{

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new NodesModel;
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            try {
                $this->modelClass->addNode($data);
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
                $this->modelClass->editNode($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success('修改成功！', url('index'));
        } else {
            $id = $this->request->param('id/d', 0);
            if (empty($id)) {
                $this->error('请指定需要修改的采集点！');
            }
            $data = $this->modelClass->where('id', $id)->find();
            $this->assign('data', $data);
            return $this->fetch();
        }

    }

    //网址采集
    public function col_url_list()
    {
        set_time_limit(0);
        //@session_start();
        //\think\facade\Session::pause();
        $nid                      = $this->request->param('id/d', 0);
        $data                     = $this->modelClass->find($nid);
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
                        if (!ContentModel::where(['url' => $v['url']])->find()) {
                            $html = $event->get_content($v['url']);
                            $event->echo_msg("采集内容页：<a href='{$v['url']}' target='_blank'>{$v['url']}</a>", 'black');
                            ContentModel::create(['nid' => $nid, 'status' => 0, 'url' => $v['url'], 'title' => $v['title'], 'data' => serialize($html)]);
                        }
                    }
                }
            }
            $this->modelClass->update(['lastdate' => time(), 'id' => $nid]);
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
            $data  = ContentModel::where($where)
                ->page($page, $limit)
                ->order('id', 'desc')
                ->select();
            $total = ContentModel::where($where)->order('id', 'desc')->count();
            return json(["code" => 0, "count" => $total, "data" => $data]);
        }
        $this->assign('param', $param);
        return $this->fetch();
    }

    public function show()
    {
        $id   = $this->request->param('id/d', 0);
        $data = ContentModel::where('id', $id)->value('data');
        $this->assign('data', unserialize($data));
        return $this->fetch();
    }

    //采集数据删除
    public function content_del()
    {
        $nid  = $this->request->param('nid/d', 0);
        $ids  = $this->request->param('id/a', null);
        $type = $this->request->param('type/s', '');
        if ($type == "all") {
            ContentModel::where('nid', $nid)->delete();
        } else {
            if (empty($ids) || !$nid) {
                $this->error('参数错误！');
            }
            if (!is_array($ids)) {
                $ids = array(0 => $ids);
            }
            try {
                foreach ($ids as $id) {
                    ContentModel::where(['nid' => $nid, 'id' => $id])->delete();
                }
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
        }
        $this->success('删除成功！');
    }

    //导出
    public function export()
    {
        $id  = $this->request->param('id/d');
        $row = $this->modelClass->find($id);
        if (!$row) {
            $this->error('采集项目不存在');
        }
        $data = $row->toArray();
        return download(base64_encode(json_encode($data)), 'task_' . $id . '.txt', true);
    }

    //导入
    public function import()
    {

    }
}
