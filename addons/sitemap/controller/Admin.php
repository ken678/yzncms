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
// | sitemap管理
// +----------------------------------------------------------------------
namespace addons\sitemap\Controller;

use addons\sitemap\library\Sitemap as Sitemap_Class;
use app\addons\util\Adminaddon;
use think\Db;

class Admin extends Adminaddon
{
    protected $filename = 'sitemap.xml';
    protected $data     = [];
    protected $directory;
    protected $url_mode = 1;

    protected function initialize()
    {
        parent::initialize();
        $this->url_mode  = isset(cache("Cms_Config")['site_url_mode']) ? cache("Cms_Config")['site_url_mode'] : 1;
        $this->directory = (defined('IF_PUBLIC') ? ROOT_PATH . 'public/' : ROOT_PATH);
    }

    public function index()
    {
        if ($this->request->isPost()) {
            $Category    = cache('Category');
            $data        = $this->request->post();
            $Sitemap     = new Sitemap_Class();
            $rootUrl     = $this->request->domain();
            $data['num'] = intval($data['num']);
            $type        = isset($data['type']) ? intval($data['type']) : 1;

            $item = $this->_sitemap_item($rootUrl, intval($data['index']['priority']), $data['index']['changefreq'], time());
            $this->_add_data($item);

            //栏目
            $List = Db::name('Category')->where('status', 1)->order('id desc')->field('id,url,catdir')->select();
            if (!empty($List)) {
                foreach ($List as $vo) {
                    $cat  = $this->url_mode == 1 ? $vo['id'] : $vo['catdir'];
                    $item = $this->_sitemap_item($rootUrl . buildCatUrl($cat), intval($data['category']['priority']), $data['category']['changefreq'], time());
                    $this->_add_data($item);
                }
            }

            //单页
            /*$List = Db::name('Page')->order('updatetime desc')->field('catid,updatetime')->select();
            if (!empty($List)) {
            foreach ($List as $vo) {
            $cat  = $this->url_mode == 1 ? $vo['catid'] : (isset($Category[$vo['catid']]) ? $Category[$vo['catid']]['id'] : getCategory($vo['catid'], 'catdir'));
            $item = $this->_sitemap_item($rootUrl . buildCatUrl($cat), intval($data['content']['priority']), $data['content']['changefreq'], $vo['updatetime']);
            $this->_add_data($item);
            }
            }*/

            //列表
            $modelList = cache('Model');
            if (!empty($modelList)) {
                $num    = 1;
                $volist = [];
                foreach ($modelList as $vo) {
                    if ($vo['module'] == "cms") {
                        $volist = Db::name($vo['tablename'])->where('status', 1)->order('updatetime desc')->field('id,catid,updatetime')->select();
                        if (!empty($volist)) {
                            foreach ($volist as $v) {
                                $cat  = $this->url_mode == 1 ? $v['catid'] : (isset($Category[$v['catid']]) ? $Category[$v['catid']]['catdir'] : getCategory($v['catid'], 'catdir'));
                                $item = $this->_sitemap_item($rootUrl . buildContentUrl($cat, $v['id']), intval($data['content']['priority']), $data['content']['changefreq'], $v['updatetime']);
                                $this->_add_data($item);
                                $num++;
                                if ($num >= $data['num']) {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            //标签
            $tags = Db::name('tags')->order('create_time desc')->field('tag,update_time')->select();
            if (!empty($tags)) {
                foreach ($tags as $vo) {
                    $item = $this->_sitemap_item($rootUrl . url('cms/index/tags', ['tag' => $vo['tag']]), intval($data['tag']['priority']), $data['tag']['changefreq'], time());
                    $this->_add_data($item);
                }
            }
            if (!$type) {
                try {
                    foreach ($this->data as $val) {
                        $Sitemap->AddItem($val['loc'], $val['priority'], $val['changefreq'], $val['lastmod']);
                    }
                    $Sitemap->SaveToFile($this->directory . $this->filename);
                } catch (\Exception $ex) {
                    $this->error($ex->getMessage());
                }
            } else {
                $str            = $this->_txt_format();
                $this->filename = 'sitemap.txt';
                @file_put_contents($this->directory . $this->filename, $str);
            }
            $this->success($this->filename . "文件已生成到运行根目录");

        } else {

            if (is_file($this->directory . 'sitemap.xml')) {
                $make_xml_time = date('Y-m-d H:i:s', filemtime($this->directory . 'sitemap.xml'));
                $this->assign('make_xml_time', $make_xml_time);
            }
            if (is_file($this->directory . 'sitemap.txt')) {
                $make_txt_time = date('Y-m-d H:i:s', filemtime($this->directory . 'sitemap.txt'));
                $this->assign('make_txt_time', $make_txt_time);
            }
            return $this->fetch();
        }
    }

    /**
     * 添加数据
     */
    private function _add_data($new_item)
    {
        $this->data[] = $new_item;
    }

    /**
     * 生成txt格式
     */
    private function _txt_format()
    {
        $str = '';
        foreach ($this->data as $val) {
            $str .= $val['loc'] . PHP_EOL;
        }
        return $str;
    }

    /**
     * 创建地图格式
     */
    private function _sitemap_item($loc, $priority = '', $changefreq = '', $lastmod = '')
    {
        $data               = array();
        $data['loc']        = $loc;
        $data['priority']   = $priority;
        $data['changefreq'] = $changefreq;
        $data['lastmod']    = $lastmod;
        return $data;
    }

}
