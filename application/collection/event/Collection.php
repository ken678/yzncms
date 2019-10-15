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
    /**
     * 得到需要采集的网页列表页
     * @param array $config 配置参数
     * @param integer $num  返回数
     */
    public function url_list(&$config, $num = '')
    {
        $url = array();
        switch ($config['sourcetype']) {
            case '1': //序列化
                $num = empty($num) ? $config['pagesize_end'] : $num;
                if ($num < $config['pagesize_start']) {
                    $num = $config['pagesize_start'];
                }
                for ($i = $config['pagesize_start']; $i <= $num; $i = $i + $config['par_num']) {
                    $url[$i] = str_replace('(*)', $i, $config['urlpage']);
                }
                break;
            case '2': //多网址
                $url = explode("\r\n", $config['urlpage']);
                break;
            case '3': //单一网址
            case '4': //RSS
                $url[] = $config['urlpage'];
                break;
        }
        return $url;
    }

    /**
     * 获取文章网址
     * @param string $url           采集地址
     * @param array $config         配置
     */
    public function get_url_lists($url, &$config)
    {
        // 定义采集规则
        $rules = [
            'url' => [$config['url_rule1'], $config['url_rule2'], $config['url_rule3'], function ($content) {
                dump($url);

                return $content;
            }],
            'title' => [$config['url_rule1'], 'html', $config['url_rule3']],
        ];
        dump($rules);
        $list = QueryList::get($url)->rules($rules)->query()->getData();
        dump($list);
    }

    /**
     * URL地址检查
     * @param string $url      需要检查的URL
     * @param string $baseurl  基本URL
     * @param array $config    配置信息
     */
    protected function url_check($url, $baseurl, $config)
    {
        $urlinfo = parse_url($baseurl);

        $baseurl = $urlinfo['scheme'] . '://' . $urlinfo['host'] . (substr($urlinfo['path'], -1, 1) === '/' ? substr($urlinfo['path'], 0, -1) : str_replace('\\', '/', dirname($urlinfo['path']))) . '/';
        if (strpos($url, '://') === false) {
            if ($url[0] == '/') {
                $url = $urlinfo['scheme'] . '://' . $urlinfo['host'] . $url;
            } else {
                if ($config['page_base']) {
                    $url = $config['page_base'] . $url;
                } else {
                    $url = $baseurl . $url;
                }
            }
        }
        return $url;
    }

}
