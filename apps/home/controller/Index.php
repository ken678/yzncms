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
namespace app\home\controller;

use app\common\controller\Homebase;
use think\Db;
use think\Loader;

/**
 * 前台
 */
class Index extends Homebase
{

    /**
     * 首页
     */
    public function index()
    {
        $SEO = seo();
        $this->assign("SEO", $SEO);
        return $this->fetch();
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
        //生成类型为0的栏目
        if ($category['type'] == 0) {
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
            //单页
        } else if ($category['type'] == 1) {
            $template = $setting['page_template'] ? $setting['page_template'] : 'page';
            //判断使用模板类型，如果有子栏目使用频道页模板，终极栏目使用的是列表模板
            $template = "{$template}";
            //去除后缀
            $tpar = explode(".", $template, 2);
            $template = $tpar[0];
            unset($tpar);
            $info = Loader::model('content/page')->getPage($catid);
            $this->assign($info);
        }
        //获取顶级栏目ID
        $arrparentid = explode(',', $category['arrparentid']);
        $top_parentid = $arrparentid[1] ? $arrparentid[1] : $catid;
        $this->assign("top_parentid", $top_parentid);
        //分配变量到模板
        $this->assign($category);
        $seo = seo($catid, $setting['meta_title'], $setting['meta_description'], $setting['meta_keywords']);
        $this->assign("SEO", $seo);
        return $this->fetch($template);
    }

    /**
     * 内容页
     */
    public function shows()
    {
        $catid = $this->request->param('catid/d', 0);
        $id = $this->request->param('id/d', 0);
        //获取当前栏目数据
        $category = getCategory($catid);
        if (empty($category)) {
            $this->error('栏目不存在！');
        }
        $modelid = $category['modelid'];
        $table_name = get_table_name($modelid);
        $r = Db::name($table_name)->where(array('id' => $id, 'status' => 99))->find();
        $r2 = Db::name($table_name . '_data')->where(array('id' => $id))->find();
        $rs = $r2 ? array_merge($r, $r2) : $r;

        $content_output = new \content_output($modelid);
        //获取字段类型处理以后的数据
        $output_data = $content_output->get($rs);
        $output_data['id'] = $id;
        $output_data['title'] = strip_tags($output_data['title']);
        //SEO
        $seo_keywords = '';
        if (!empty($output_data['keywords'])) {
            $seo_keywords = implode(',', $output_data['keywords']);
        }
        $seo = seo($catid, $setting['meta_title'], $setting['meta_description'], $setting['meta_keywords']);

        //内容页模板
        $template = $output_data['template'] ? $output_data['template'] : $category['setting']['show_template'];
        //去除模板文件后缀
        $newstempid = explode(".", $template);
        $template = $newstempid[0];
        unset($newstempid);
        $this->assign("SEO", $seo);
        //分配解析后的文章数据到模板
        $this->assign($output_data);
        return $this->fetch($template);
    }

}
