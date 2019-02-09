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

class Index extends Homebase
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 首页
     */
    public function index()
    {
        $SEO = seo();
        $this->assign("SEO", $SEO);
        return $this->fetch('/index');
    }

    /**
     * 列表页
     */
    public function lists()
    {
        //栏目ID
        $catid = $this->request->param('catid/d', 0);
        //获取栏目数据
        $category = getCategory($catid);
        if (empty($category)) {
            $this->error('栏目不存在！');
        }
        //栏目扩展配置信息
        $setting = $category['setting'];
        //生成类型为2的栏目
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
            $keywords = $info['keywords'] ? $info['keywords'] : $setting['meta_keywords'];
            $title = $info['title'] ? $info['title'] : $setting['meta_title'];
            $description = $info['description'] ? $info['description'] : $setting['meta_description'];
            $seo = seo($catid, $title, $description, $keywords);
            $this->assign('page', $info);
        }
        $this->assign("SEO", $seo);
        $this->assign('category', $category);
        return $this->fetch('/' . $template);
    }

    /**
     * 内容页
     */
    public function shows()
    {
        return $this->fetch('/' . $template);

    }

}
