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

use app\cms\controller\Homebase;
use app\cms\model\Cms as Cms_Model;
use think\Db;

class Index extends Homebase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Cms_Model = new Cms_Model;
    }

    /**
     * 首页
     */
    public function index()
    {
        $page = $this->request->param('page/d', 1);
        $seo = seo();
        $this->assign([
            'SEO' => $seo,
            'page' => $page,
        ]);
        return $this->fetch('/index');
    }

    /**
     * 列表页
     */
    public function lists()
    {
        //栏目ID
        $catid = $this->request->param('catid/d', 0);
        $page = $this->request->param('page/d', 1);
        //获取栏目数据
        $category = getCategory($catid);
        //模型ID
        $modelid = $category['modelid'];
        $models = cache('Model');
        $modelInfo = $models[$modelid];
        if (empty($category)) {
            $this->error('栏目不存在！');
        }
        if ($this->request->isPost()) {
            //投稿
            if (!isset($modelInfo)) {
                $this->error('栏目禁止投稿~');
            }
            if (!$modelInfo['ifsub']) {
                $this->error($modelInfo['title'] . '模型禁止投稿~');
            }

            $data = $this->request->post();
            // 验证码
            if (!captcha_check($data['captcha'])) {
                $this->error('验证码错误或失效');
            }
            //令牌验证
            $vresult = $this->validate($data, ['__token__|令牌' => 'require|token']);
            if (true !== $vresult) {
                $this->error($vresult);
            }
            $data['modelField']['catid'] = $catid;
            $data['modelField']['listorder'] = 100;
            $data['modelField']['status'] = 0;
            $data['modelFieldExt'] = isset($data['modelFieldExt']) ? $data['modelFieldExt'] : [];
            try {
                $this->Cms_Model->addModelData($data['modelField'], $data['modelFieldExt']);
            } catch (\Exception $ex) {
                $this->error($ex->getMessage());
            }
            $this->success($modelInfo['title'] . '提交成功~');
        } else {
            //栏目扩展配置信息
            $setting = $category['setting'];
            //类型为列表的栏目
            if ($category['type'] == 2) {
                //栏目首页模板
                $template = $setting['category_template'] ? $setting['category_template'] : 'category';
                //栏目列表页模板
                $template_list = $setting['list_template'] ? $setting['list_template'] : 'list';
                //判断使用模板类型，如果有子栏目使用频道页模板
                $template = $category['child'] ? "{$template}" : "{$template_list}";
                $tpar = explode(".", $template, 2);
                //去除完后缀的模板
                $template = $tpar[0];
                unset($tpar);
                $seo = seo($catid, $setting['meta_title'], $setting['meta_description'], $setting['meta_keywords']);
                //单页
            } else if ($category['type'] == 1) {
                $template = $setting['page_template'] ? $setting['page_template'] : 'page';
                //判断使用模板类型，如果有子栏目使用频道页模板，终极栏目使用的是列表模板
                $template = "{$template}";
                //去除后缀
                $tpar = explode(".", $template, 2);
                $template = $tpar[0];
                unset($tpar);
                $info = model('Page')->getPage($catid);
                if ($info) {
                    $info = $info->toArray();
                }
                //SEO
                $keywords = $info['keywords'] ? $info['keywords'] : $setting['meta_keywords'];
                $title = $info['title'] ? $info['title'] : $setting['meta_title'];
                $description = $info['description'] ? $info['description'] : $setting['meta_description'];
                $seo = seo($catid, $title, $description, $keywords);
                $this->assign($info);
            }
            //获取顶级栏目ID
            $arrparentid = explode(',', $category['arrparentid']);
            $top_parentid = isset($arrparentid[1]) ? $arrparentid[1] : $catid;
            //投稿
            if (isset($modelInfo['ifsub']) && $modelInfo['ifsub']) {
                $fieldList = $this->Cms_Model->getFieldList($modelInfo['id']);
                $this->assign('fieldList', $fieldList);
            }
            $this->assign([
                'top_parentid' => $top_parentid,
                'SEO' => $seo,
                'catid' => $catid,
                'page' => $page,
            ]);
            return $this->fetch('/' . $template);

        }

    }

    /**
     * 内容页
     */
    public function shows()
    {
        //ID
        $id = $this->request->param('id/d', 0);
        //栏目ID
        $catid = $this->request->param('catid/d', 0);
        $page = $page = $this->request->param('page/d', 1);

        //获取栏目数据
        $category = getCategory($catid);
        if (empty($category)) {
            $this->error('栏目不存在！');
        }
        //模型ID
        $modelid = $category['modelid'];

        //更新点击量
        //Db::name($modelInfo['tablename'])->where('id', $id)->inc('hits')->update();

        //内容所有字段
        $info = $this->Cms_Model->getDataInfo($modelid, "id='" . $id . "'", true);
        if (empty($info)) {
            abort(404, '内容不存在或未审核');
        }
        //栏目扩展配置信息
        $setting = $category['setting'];
        //内容页模板
        $template = $setting['show_template'] ? $setting['show_template'] : 'show';
        //去除模板文件后缀
        $newstempid = explode(".", $template);
        $template = $newstempid[0];
        unset($newstempid);

        //SEO
        $keywords = $info['keywords'] ? $info['keywords'] : $setting['meta_keywords'];
        $title = $info['title'] ? $info['title'] : $setting['meta_title'];
        $description = $info['description'] ? $info['description'] : $setting['meta_description'];
        $seo = seo($catid, $title, $description, $keywords);

        //获取顶级栏目ID
        $arrparentid = explode(',', $category['arrparentid']);
        $top_parentid = isset($arrparentid[1]) ? $arrparentid[1] : $catid;

        $this->assign($info);
        $this->assign([
            'top_parentid' => $top_parentid,
            'SEO' => $seo,
            'catid' => $catid,
            'page' => $page,
        ]);
        return $this->fetch('/' . $template);
    }

    /**
     * 搜索
     */
    public function search()
    {
        $seo = seo('', '搜索结果');
        //模型
        $modelid = $this->request->param('modelid/d', 0);
        //关键词
        $keyword = $this->request->param('keyword/s', '', 'trim,safe_replace,strip_tags,htmlspecialchars');
        $keyword = str_replace('%', '', $keyword); //过滤'%'，用户全文搜索
        //时间范围
        $time = $this->request->param('time/s', '');

        $result = $this->validate([
            'keyword' => $keyword,
        ], [
            'keyword|标题关键词' => 'chsDash|max:25',
        ]);
        if (true !== $result) {
            $this->error($result);
        }
        debug('begin');
        //按时间搜索
        if ($time == 'day') {
            $search_time = time() - 86400;
            $sql_time = ' AND inputtime > ' . $search_time;
        } elseif ($time == 'week') {
            $search_time = time() - 604800;
            $sql_time = ' AND inputtime > ' . $search_time;
        } elseif ($time == 'month') {
            $search_time = time() - 2592000;
            $sql_time = ' AND inputtime > ' . $search_time;
        } elseif ($time == 'year') {
            $search_time = time() - 31536000;
            $sql_time = ' AND inputtime > ' . $search_time;
        } else {
            $search_time = 0;
            $sql_time = '';
        }
        //搜索历史记录
        $shistory = cookie("shistory");
        if (!$shistory) {
            $shistory = array();
        }
        array_unshift($shistory, $keyword);
        $shistory = array_slice(array_unique($shistory), 0, 10);
        //加入搜索历史
        cookie("shistory", $shistory);

        $modellist = cache('Model');
        if (!$modellist) {
            return $this->error('没有可搜索模型~');
        }

        if ($modelid) {
            if (!array_key_exists($modelid, $modellist)) {
                $this->error('模型错误~');
            }
            $searchField = Db::name('model_field')->where('modelid', $modelid)->where('ifsystem', 1)->where('ifsearch', 1)->column('name');
            if (empty($searchField)) {
                $this->error('没有设置搜索字段~');
            }
            $where = '';
            foreach ($searchField as $vo) {
                $where .= "$vo like '%$keyword%' or ";
            }
            $where = '(' . substr($where, 0, -4) . ') ';
            $where .= " AND status='1' $sql_time";
            $list = $this->Cms_Model->getDataList($modelid, $where, false, '*', "listorder,id desc", 10, 1);
        } else {
            foreach ($modellist as $key => $vo) {
                $searchField = Db::name('model_field')->where('modelid', $key)->where('ifsystem', 1)->where('ifsearch', 1)->column('name');
                if (empty($searchField)) {
                    continue;
                }
                $where = '';
                foreach ($searchField as $v) {
                    $where .= "$v like '%$keyword%' or ";
                }
                $where = '(' . substr($where, 0, -4) . ') ';
                $where .= " AND status='1' $sql_time";
                $list = $this->Cms_Model->getDataList($key, $where, false, '*', 'listorder,id desc', 10, 1);
                if ($list->isEmpty()) {
                    continue;
                } else {
                    break;
                }
            }
        }
        $count = $list->total();
        debug('end');
        $this->assign([
            'time' => $time,
            'modelid' => $modelid,
            'keyword' => $keyword,
            'shistory' => $shistory,
            'SEO' => $seo,
            'list' => $list,
            'count' => $count,
            'modellist' => $modellist,
            'search_time' => debug('begin', 'end', 6), //运行时间
            'page' => $list->render(),
        ]);
        if (!empty($keyword)) {
            return $this->fetch('/search_result');
        } else {
            return $this->fetch('/search');
        }

    }

}
