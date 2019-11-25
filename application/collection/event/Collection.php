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
    public $_url;
    public function init($config)
    {
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
        if ($url) {
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
                $data[$k]['url'] = $this->url_check($v['url'], $url);
                $data[$k]['title'] = strip_tags($v['title']);
            }
            return $data;
        } else {
            return false;
        }
    }

    //采集内容
    public function get_content($url)
    {
        $this->_url = $url;
        foreach ($this->_config['customize_config'] as $k => $v) {
            if (empty($v['name'])) {
                continue;
            }
            $rules[$v['name']] = [$v['selector'], $v['attr'], $v['filter'], function ($content) use ($v) {
                if (!empty($v['value'])) {
                    return $v['value'];
                }
                if ("html" == $v['attr']) {
                    $content = preg_replace_callback('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/i', array(&$this, 'download_img_callback'), $content);
                }
                return $content;
            }];
        }
        $cont = QueryList::get($url)->rules($rules)->query()->getData();
        return $cont[0];

    }

    /**
     * 转换图片地址为绝对路径，为下载做准备。
     * @param array $out 图片地址
     */
    protected function download_img_callback($matches)
    {
        return $this->download_img($matches[0], $matches[1]);
    }
    protected function download_img($old, $out)
    {
        if (!empty($old) && !empty($out) && strpos($out, '://') === false) {
            return str_replace($out, $this->url_check($out, $this->_url), $old);
        } else {
            return $old;
        }
    }

    //URL地址检查
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

    /*输出内容函数*/
    private static $echo_msg_head = null;
    public function echo_msg($str, $color = 'red', $echo = true, $end_str = '')
    {

        if (!isset(self::$echo_msg_head)) {
            self::$echo_msg_head = true;
            header('Content-type: text/html; charset=utf-8');
            header('X-Accel-Buffering: no');
            @ini_set('output_buffering', 'Off');

            ob_end_clean();
            @ob_implicit_flush(1);

            $outputSize = ini_get('output_buffering');
            $outputSize = intval($outputSize);

            if (preg_match('/\biis\b/i', $_SERVER["SERVER_SOFTWARE"])) {

                if ($outputSize < 1024 * 1024 * 4) {

                    $outputSize = 1024 * 1024 * 4;
                    echo '<!-- iis默认需输出4mb数据才能实时显示-->';
                }
            }
            echo '<style type="text/css">body{padding:0 5px;font-size:14px;color:#000;}p{padding:0;margin:0;}a{color:#aaa;}</style>';
            echo str_pad(' ', 1024 * 4);
        }
        echo '<p style="color:' . $color . ';">' . $str . '</p>' . $end_str;
        if (ob_get_level() > 0) {
            ob_flush();
            flush();
        }

    }

}
