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

use app\admin\service\User;
use app\cms\model\Cms as Cms_Model;
use app\cms\model\Page as Page_Model;
use app\common\controller\Adminbase;
use think\Db;

class Cms extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Cms_Model = new Cms_Model;
        $this->cmsConfig = cache("Cms_Config");
        $this->assign("cmsConfig", $this->cmsConfig);
    }

    public function index()
    {
        return $this->fetch();
    }

    //栏目信息列表
    public function classlist()
    {
        $catid = $this->request->param('catid/d', 0);
        //当前栏目信息
        $catInfo = getCategory($catid);
        if (empty($catInfo)) {
            $this->error('该栏目不存在！');
        }
        //栏目所属模型
        $modelid = $catInfo['modelid'];
        if ($this->request->isAjax()) {
            //检查模型是否被禁用
            if (!getModel($modelid, 'status')) {
                $this->error('模型被禁用！');
            }
            $modelCache = cache("Model");
            $tableName  = $modelCache[$modelid]['tablename'];

            $this->modelClass = Db::name($tableName);
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($page, $limit, $where) = $this->buildTableParames();

            $conditions = [
                ['catid', '=', $catid],
                ['status', '<>', -1],
            ];
            $total = Db::name($tableName)->where($where)->where($conditions)->count();
            $list  = Db::name($tableName)->page($page, $limit)->where($where)->where($conditions)->order(['listorder', 'id' => 'desc'])->select();
            $_list = [];
            foreach ($list as $k => $v) {
                $v['updatetime'] = date('Y-m-d H:i:s', $v['updatetime']);
                if (isset($v['thumb'])) {
                    $v['thumb'] = get_file_path($v['thumb']);
                }
                $v['url'] = buildContentUrl($v['catid'], $v['id'], $v['url']);
                $_list[]  = $v;
            }
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        /*移动栏目 复制栏目*/
        $tree       = new \util\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $categorys  = array();
        $result     = Db::name('category')->order(array('listorder', 'id' => 'ASC'))->select();
        foreach ($result as $k => $v) {
            if ($v['type'] != 2) {
                $v['disabled'] = 'disabled';
            }
            if ($modelid && $modelid != $v['modelid']) {
                $v['disabled'] = 'disabled';
            }
            //$v['disabled'] = $v['child'] ? 'disabled' : '';
            $v['selected'] = $v['id'] == $catid ? 'selected' : '';
            $categorys[$k] = $v;
        }
        $str = "<option value=@id @selected @disabled>@spacer @catname</option>";
        $tree->init($categorys);
        $string = $tree->getTree(0, $str, $catid);

        $this->assign('string', $string);
        $this->assign('catid', $catid);
        return $this->fetch();
    }

    //移动文章
    public function remove()
    {
        $this->check_priv('remove');
        if ($this->request->isPost()) {
            $catid = $this->request->param('catid/d', 0);
            if (!$catid) {
                $this->error("请指定栏目！");
            }
            //需要移动的信息ID集合
            $ids = $this->request->param('ids/s');
            //目标栏目
            $tocatid = $this->request->param('tocatid/d', 0);
            if ($ids) {
                if ($tocatid == $catid) {
                    $this->error('目标栏目和当前栏目是同一个栏目！');
                }
                $modelid = getCategory($tocatid, 'modelid');
                if (!$modelid) {
                    $this->error('该模型不存在！');
                }
                $ids       = array_filter(explode('|', $ids), 'intval');
                $tableName = Db::name('model')->where('id', $modelid)->where('status', 1)->value('tablename');
                if (!$tableName) {
                    $this->error('模型被冻结不可操作~');
                }
                if (Db::name(ucwords($tableName))->where('id', 'in', $ids)->update(['catid' => $tocatid])) {
                    Db::name('Category')->where('id', $catid)->setDec('items', count($ids));
                    Db::name('Category')->where('id', $tocatid)->setInc('items', count($ids));
                    $this->success('移动成功~');
                } else {
                    $this->error('移动失败~');
                }
            } else {
                $this->error('请选择需要移动的信息！');
            }
        }
    }

    //添加信息
    public function add()
    {
        $this->check_priv('add');
        if ($this->request->isPost()) {
            $data  = $this->request->post();
            $catid = intval($data['modelField']['catid']);
            if (empty($catid)) {
                $this->error("请指定栏目ID！");
            }
            $category = getCategory($catid);
            if (empty($category)) {
                $this->error('该栏目不存在！');
            }
            if ($category['type'] == 2) {
                $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
                try {
                    $this->Cms_Model->addModelData($data['modelField'], $data['modelFieldExt']);
                } catch (\Exception $ex) {
                    $this->error($ex->getMessage());
                }
            } else if ($category['type'] == 1) {
                $Page_Model = new Page_Model;
                if (!$Page_Model->savePage($data['modelField'])) {
                    $error = $Page_Model->getError();
                    $this->error($error ? $error : '操作失败！');
                }
            }
            $this->success('操作成功！');
        } else {
            $catid    = $this->request->param('catid/d', 0);
            $category = getCategory($catid);
            if (empty($category)) {
                $this->error('该栏目不存在！');
            }
            if ($category['type'] == 2) {
                $modelid   = $category['modelid'];
                $fieldList = $this->Cms_Model->getFieldList($modelid);
                $this->assign([
                    'catid'     => $catid,
                    'fieldList' => $fieldList,
                ]);
                return $this->fetch();
            } else if ($category['type'] == 1) {
                $Page_Model = new Page_Model;
                $info       = $Page_Model->getPage($catid);
                $this->assign([
                    'info'  => $info,
                    'catid' => $catid,
                ]);
                return $this->fetch('singlepage');
            }

        }
    }

    //编辑信息
    public function edit()
    {
        $this->check_priv('edit');
        if ($this->request->isPost()) {
            $data                  = $this->request->post();
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $this->Cms_Model->editModelData($data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success('编辑成功！');

        } else {
            $catid    = $this->request->param('catid/d', 0);
            $id       = $this->request->param('id/d', 0);
            $category = getCategory($catid);
            if (empty($category)) {
                $this->error('该栏目不存在！');
            }
            if ($category['type'] == 2) {
                $modelid   = $category['modelid'];
                $fieldList = $this->Cms_Model->getFieldList($modelid, $id);
                $this->assign([
                    'catid'     => $catid,
                    'id'        => $id,
                    'fieldList' => $fieldList,
                ]);
                return $this->fetch();
            } else {
                return $this->fetch('singlepage');
            }
        }
    }

    //删除
    public function del()
    {
        $this->check_priv('delete');
        $catid = $this->request->param('catid/d', 0);
        $ids   = $this->request->param('ids/a', null);
        if (empty($ids) || !$catid) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        $modelid = getCategory($catid, 'modelid');
        try {
            foreach ($ids as $id) {
                $this->Cms_Model->deleteModelData($modelid, $id, $this->cmsConfig['web_site_recycle']);
            }
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }

        $this->success('删除成功！');
    }

    //清空回收站
    public function destroy()
    {
        $catid = $this->request->param('catid/d', 0);
        $ids   = $this->request->param('ids/a', null);
        if (empty($ids) || !$catid) {
            $this->error('参数错误！');
        }
        if (!is_array($ids)) {
            $ids = array(0 => $ids);
        }
        $modelid = getCategory($catid, 'modelid');
        try {
            foreach ($ids as $id) {
                $this->Cms_Model->deleteModelData($modelid, $id);
            }
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }

        $this->success('销毁成功！');
    }

    //面板
    public function panl()
    {
        if ($this->request->isPost()) {
            $date                         = $this->request->post('date');
            list($xAxisData, $seriesData) = $this->getAdminPostData($date);
            $this->success('', '', ['xAxisData' => $xAxisData, 'seriesData' => $seriesData]);
        } else {
            $info['category'] = Db::name('Category')->count();
            $info['model']    = Db::name('Model')->where(['module' => 'cms'])->count();
            $info['tags']     = Db::name('Tags')->count();
            $info['doc']      = 0;
            $modellist        = cache('Model');
            foreach ($modellist as $model) {
                if ($model['module'] !== 'cms') {
                    continue;
                }
                $tmp = Db::name($model['tablename'])->count();
                $info['doc'] += $tmp;
            }
            list($xAxisData, $seriesData) = $this->getAdminPostData();
            $this->assign('xAxisData', $xAxisData);
            $this->assign('seriesData', $seriesData);

            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    protected function getAdminPostData($date = '')
    {
        if ($date) {
            list($start, $end) = explode(' - ', $date);
            $start_time        = strtotime($start);
            $end_time          = strtotime($end);
        } else {
            $start_time = \util\Date::unixtime('day', 0, 'begin');
            $end_time   = \util\Date::unixtime('day', 0, 'end');
        }
        $diff_time = $end_time - $start_time;
        $format    = '%Y-%m-%d';
        if ($diff_time > 86400 * 30 * 2) {
            $format = '%Y-%m';
        } else {
            if ($diff_time > 86400) {
                $format = '%Y-%m-%d';
            } else {
                $format = '%H:00';
            }
        }
        //获取所有表名
        $models = array_values(cache('Model'));
        $list   = $xAxisData   = $seriesData   = [];
        if (count($models) > 0) {
            $table1 = $models[0]['tablename'];
            unset($models[0]);
            $field = 'a.username,uid,FROM_UNIXTIME(inputtime, "' . $format . '") as inputtimes,COUNT(*) AS num';
            $dbObj = Db::name($table1)->alias('b')->field($field)->where('inputtime', 'between time', [$start_time, $end_time])->join('admin a', 'a.id = b.uid');
            foreach ($models as $k => $v) {
                $dbObj->union(function ($query) use ($field, $start_time, $end_time, $v) {
                    $query->name($v['tablename'])->alias('b')->field($field)->where('inputtime', 'between time', [$start_time, $end_time])->join('admin a', 'a.id = b.uid')->group('uid,inputtimes');
                });
            };
            $res = $dbObj->group('uid,inputtimes')->select();
            if ($diff_time > 84600 * 30 * 2) {
                $start_time = strtotime('last month', $start_time);
                while (($start_time = strtotime('next month', $start_time)) <= $end_time) {
                    $column[] = date('Y-m', $start_time);
                }
            } else {
                if ($diff_time > 86400) {
                    for ($time = $start_time; $time <= $end_time;) {
                        $column[] = date("Y-m-d", $time);
                        $time += 86400;
                    }
                } else {
                    for ($time = $start_time; $time <= $end_time;) {
                        $column[] = date("H:00", $time);
                        $time += 3600;
                    }
                }
            }
            $xAxisData = array_fill_keys($column, 0);
            foreach ($res as $k => $v) {
                if (!isset($list[$v['username']])) {
                    $list[$v['username']] = $xAxisData;
                }
                $list[$v['username']][$v['inputtimes']] = $v['num'];
            }
            foreach ($list as $index => $item) {
                $seriesData[] = [
                    'name'      => $index,
                    'type'      => 'line',
                    'smooth'    => true,
                    'areaStyle' => [],
                    'data'      => array_values($item),
                ];
            }
        }
        return [array_keys($xAxisData), $seriesData];
    }

    //显示栏目菜单列表
    public function public_categorys()
    {
        $isAdministrator = User::instance()->isAdministrator();
        $json            = $priv_catids            = [];

        if (0 !== (int) $this->cmsConfig['site_category_auth']) {
            //栏目权限 超级管理员例外
            if ($isAdministrator !== true) {
                $role_id     = User::instance()->roleid;
                $priv_result = Db::name('CategoryPriv')->where(['roleid' => $role_id, 'action' => 'init'])->select();
                foreach ($priv_result as $_v) {
                    $priv_catids[] = $_v['catid'];
                }
            }
        }
        $categorys = Db::name('Category')->order(array('listorder', 'id' => 'ASC'))->select();
        foreach ($categorys as $rs) {
            //剔除无子栏目外部链接
            if ($rs['type'] == 3 && $rs['child'] == 0) {
                continue;
            }
            if (0 !== (int) $this->cmsConfig['site_category_auth']) {
                //只显示有init权限的，超级管理员除外
                if ($isAdministrator !== true && !in_array($rs['id'], $priv_catids)) {
                    $arrchildid      = explode(',', $rs['arrchildid']);
                    $array_intersect = array_intersect($priv_catids, $arrchildid);
                    if (empty($array_intersect)) {
                        continue;
                    }
                }
            }
            $data = array(
                'id'       => $rs['id'],
                'parentid' => $rs['parentid'],
                'catname'  => $rs['catname'],
                'type'     => $rs['type'],
            );
            //终极栏目
            if ($rs['child'] !== 0) {
                $data['isParent'] = true;
            }
            $data['target'] = 'right';
            $data['url']    = url('cms/cms/classlist', array('catid' => $rs['id']));
            //单页
            if ($rs['type'] == 1) {
                $data['target'] = 'right';
                $data['url']    = url('cms/cms/add', array('catid' => $rs['id']));
            }
            $json[] = $data;
        }
        $this->assign('json', json_encode($json));
        return $this->fetch();
    }

    /**
     * 排序
     */
    public function listorder()
    {
        $this->check_priv('listorder');
        $catid      = $this->request->param('catid/d', 0);
        $id         = $this->request->param('id/d', 0);
        $listorder  = $this->request->param('value/d', 0);
        $modelid    = getCategory($catid, 'modelid');
        $modelCache = cache("Model");
        if (empty($modelCache[$modelid])) {
            return false;
        };
        $tableName = $modelCache[$modelid]['tablename'];
        if (Db::name($tableName)->where('id', $id)->update(['listorder' => $listorder])) {
            $this->success("排序成功！");
        } else {
            $this->error("排序失败！");
        }
    }

    //回收站
    public function recycle()
    {
        $catid = $this->request->param('catid/d', 0);
        //当前栏目信息
        $catInfo = getCategory($catid);
        if (empty($catInfo)) {
            $this->error('该栏目不存在！');
        }
        //栏目所属模型
        $modelid = $catInfo['modelid'];
        if ($this->request->isAjax()) {
            $modelCache                 = cache("Model");
            $tableName                  = $modelCache[$modelid]['tablename'];
            $this->modelClass           = Db::name($tableName);
            list($page, $limit, $where) = $this->buildTableParames();
            $conditions                 = [
                ['catid', '=', $catid],
                ['status', '=', -1],
            ];
            $total = Db::name($tableName)->where($where)->where($conditions)->count();
            $_list = Db::name($tableName)->where($where)->page($page, $limit)->where($conditions)->order(['listorder', 'id' => 'desc'])->select();

            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        $this->assign('catid', $catid);
        return $this->fetch();
    }

    //还原回收站
    public function restore()
    {
        $catid = $this->request->param('catid/d', 0);
        //当前栏目信息
        $catInfo = getCategory($catid);
        if (empty($catInfo)) {
            $this->error('该栏目不存在！');
        }
        //栏目所属模型
        $modelid   = $catInfo['modelid'];
        $ids       = $this->request->param('ids');
        $modelInfo = cache('Model');
        $modelInfo = $modelInfo[$modelid];
        if ($ids) {
            if (!is_array($ids)) {
                $ids = array(0 => $ids);
            }
            Db::name($modelInfo['tablename'])->where('id', 'in', $ids)->setField('status', 1);
        }
        $this->success('还原成功！');
    }

    //状态
    public function setstate()
    {
        $this->check_priv('status');
        $catid      = $this->request->param('catid/d', 0);
        $id         = $this->request->param('id/d', 0);
        $status     = $this->request->param('value/d');
        $modelid    = getCategory($catid, 'modelid');
        $modelCache = cache("Model");
        if (empty($modelCache[$modelid])) {
            return false;
        };
        $tableName = ucwords($modelCache[$modelid]['tablename']);
        if (Db::name($tableName)->where('id', $id)->update(['status' => $status])) {
            //更新栏目缓存
            cache('Category', null);
            getCategory($id, '', true);
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }

    public function check_title()
    {
        $title = $this->request->param('data/s', '');
        $catid = $this->request->param('catid/d', 0);
        $id    = $this->request->param('id/d', 0);
        if (empty($title)) {
            $this->success('标题没有重复！');
            return false;
        }
        $modelid    = getCategory($catid, 'modelid');
        $modelCache = cache("Model");
        if (empty($modelCache[$modelid])) {
            $this->error('模型不存在！');
            return false;
        };
        $tableName = ucwords($modelCache[$modelid]['tablename']);
        $repeat    = Db::name($tableName)->where('title', $title);
        empty($id) ?: $repeat->where('id', '<>', $id);
        if ($repeat->find()) {
            $this->error('标题有重复！');
        } else {
            $this->success('标题没有重复！');
        }
    }

    //批量更新
    public function multi()
    {
        // 管理员禁止批量操作
        $this->error();
    }

    protected function check_priv($action)
    {
        if (User::instance()->isAdministrator() !== true) {
            if (0 !== (int) $this->cmsConfig['site_category_auth']) {
                $catid      = $this->request->param('catid/d', 0);
                $action     = getCategory($catid, 'type') == 1 ? 'init' : $action;
                $priv_datas = Db::name('CategoryPriv')->where(['catid' => $catid, 'is_admin' => 1, 'roleid' => User::instance()->roleid, 'action' => $action])->find();
                if (empty($priv_datas)) {
                    $this->error('您没有操作该项的权限！');
                }
            }
        }
    }

}
