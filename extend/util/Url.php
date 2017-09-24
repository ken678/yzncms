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
namespace util;

class Url
{
    /**
     * 获取栏目的访问路径
     * @param type $catid 栏目id
     * @param type $page 当前分页码
     * @return array Array
     * (
     *   [url] => http://news.abc.com/ 访问地址
     *   [path] => record/index.html 生成路径 动态木有
     *   [page] => Array 用于分页
     *  (
     *    [index] => http://news.abc.com/index_{$page}.html
     *    [list] => http://news.abc.com/index.html
     *   )
     *  )
     */
    public function category_url($catid, $page = 1, $category_ruleid = false)
    {
        //栏目数据
        $category = getCategory($catid);
        if (empty($category)) {
            return false;
        }
        //外部链接直接返回外部地址
        if ($category['type'] == 2) {
            return $category['url'];
        }

        //取得规则
        $urlrule = "/home/index/lists/catid/{$catid}|/home/index/lists/catid/{$catid}/page/{$page}";
        $replace_l = array(); //需要替换的标签
        $replace_r = array(); //替换的内容
        //初始
        /*if (strstr($urlrule, '{$categorydir}')) {
        //获取当前栏目父栏目路径
        $category_dir = $this->get_categorydir($catid);
        $replace_l[] = '{$categorydir}';
        $replace_r[] = $category_dir;
        }
        if (strstr($urlrule, '{$catdir}')) {
        $replace_l[] = '{$catdir}';
        $replace_r[] = $category['catdir'];
        }*/
        $replace_l[] = '{$catid}';
        $replace_r[] = $catid;
        //标签替换
        $urlrule = str_replace($replace_l, $replace_r, $urlrule);
        $_category_url[$catid] = $urlrule;

        $urlrule = explode("|", $urlrule);
        $url = array(
            "url" => ($page > 1 ? $urlrule[1] : $urlrule[0]),
            "path" => "",
        );
        return $url;

    }

    /**
     * 生成内容页相关地址
     * @param type $data 文章数据
     * @param type $page 当前分页号
     * @return boolean
     * Array
     * (
     *     [url] => http://news.abc.com/1970/web_01/2.html 访问路径
     *     [path] => /record/1970/web_01/2.html 生成路径 动态木有
     *     [page] => Array
     *     (
     *         [index] => http://news.abc.com/1970/web_01/2.html
     *         [list] => http://news.abc.com/1970/web_01/2_{$page}.html
     *     )
     * )
     */
    public function show($data = '', $page = 1)
    {
        static $_show = array();
        if (empty($data)) {
            if (!empty($this->data)) {
                $data = $this->data;
                // 重置数据
                $this->data = array();
            } else {
                $this->error = '没有数据';
                return false;
            }
        }

        //栏目id
        $catid = (int) $data['catid'];
        //信息id
        $id = (int) $data['id'];

        $urlrule = "/home/index/shows/catid/{$catid}/id/{$id}|/home/index/shows/catid/{$catid}/id/{$id}page/{$page}";
        $replace_l = array(); //需要替换的标签
        $replace_r = array(); //替换的内容
        //栏目id
        if (strstr($urlrule, '{$catid}')) {
            $replace_l[] = '{$catid}';
            $replace_r[] = $catid;
        }
        $replace_l[] = '{$id}';
        $replace_r[] = $id;
        //标签替换
        $urlrule = str_replace($replace_l, $replace_r, $urlrule);
        $urlrule = explode("|", $urlrule);
        $url = array(
            "url" => ($page > 1 ? $urlrule[1] : $urlrule[0]),
            "path" => "",
        );
        return $url;
    }

}
