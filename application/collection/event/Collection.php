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
namespace app\collection\event;

use QL\QueryList;

class Collection
{
    public $_config;
    public function init($config)
    {
        $config['customize_config'] = unserialize($config['customize_config']);
        $this->_config = $config;
    }

    //得到需要采集的网页列表页
    public function url_list($num = '')
    {
        $url = array();
        switch ($this->_config['sourcetype']) {
            case '1': //序列化
                $num = empty($num) ? $this->_config['pagesize_end'] : $num;
                if ($num < $this->_config['pagesize_start']) {
                    $num = $this->_config['pagesize_start'];
                }
                for ($i = $this->_config['pagesize_start']; $i <= $num; $i = $i + $this->_config['par_num']) {
                    $url[$i] = str_replace('(*)', $i, $this->_config['urlpage']);
                }
                break;
            case '2': //多网址
                $url = explode("\r\n", $this->_config['urlpage']);
                break;
            case '3': //单一网址
            case '4': //RSS
                $url[] = $this->_config['urlpage'];
                break;
        }
        return $url;
    }

    //获取文章网址
    public function get_url_lists($url)
    {
        // 定义采集规则
        $rules = [
            'url' => [$this->_config['url_rule1'], $this->_config['url_rule2'], $this->_config['url_rule3']],
            'title' => [$this->_config['url_rule1'], 'html', $this->_config['url_rule3']],
        ];
        $list = QueryList::get($url)->rules($rules)->query()->getData();
        $data = array();
        foreach ($list as $k => $v) {
            if ($this->_config['url_contain']) {
                if (strpos($v['url'], $this->_config['url_contain']) === false) {
                    continue;
                }
            }
            if ($this->_config['url_except']) {
                if (strpos($v['url'], $this->_config['url_except']) !== false) {
                    continue;
                }
            }
            $data[$k]['url'] = $this->url_check($v['url'], $url, $config);
            $data[$k]['title'] = strip_tags($v['title']);
        }
        return $data;
    }

    /**
     * URL地址检查
     * @param string $url      需要检查的URL
     * @param string $baseurl  基本URL
     * @param array $config    配置信息
     */
    protected function url_check($url, $baseurl)
    {
        $urlinfo = parse_url($baseurl);
        $baseurl = $urlinfo['scheme'] . '://' . $urlinfo['host'] . (substr($urlinfo['path'], -1, 1) === '/' ? substr($urlinfo['path'], 0, -1) : str_replace('\\', '/', dirname($urlinfo['path']))) . '/';
        if (strpos($url, '://') === false) {
            if ($url[0] == '/') {
                $url = $urlinfo['scheme'] . '://' . $urlinfo['host'] . $url;
            } else {
                if (isset($this->_config['page_base'])) {
                    $url = $this->_config['page_base'] . $url;
                } else {
                    $url = $baseurl . $url;
                }
            }
        }
        return $url;
    }

}
