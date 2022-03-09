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
namespace app\collection\library;

use app\admin\service\User;
use app\attachment\model\Attachment;
use QL\QueryList;

class Collection
{
    public $_config;
    public $_url;
    public $_uid;
    public function init($config)
    {
        $this->_config = $config;
        $this->_uid    = User::instance()->isLogin();
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
                'url'   => [$this->_config['url_rule1'], $this->_config['url_rule2'], $this->_config['url_rule3']],
                'title' => [$this->_config['url_rule4'], $this->_config['url_rule5'], $this->_config['url_rule6']],
            ];
            if ('utf-8' == $this->_config['sourcecharset']) {
                $obj = QueryList::get($url);
            } else {
                $obj = QueryList::get($url)->encoding('UTF-8')->removeHead();
            }
            $list = $obj->rules($rules)->range($this->_config['url_range'])->query()->getData()->all();
            QueryList::destructDocuments();
            $data = array();
            foreach ($list as $k => $v) {
                if (empty($v['url']) || empty($v['title'])) {
                    continue;
                }
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
                $data[$k]['url']   = $this->url_check($v['url'], $url);
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
                    if (false !== strpos($content, '<img')) {
                        $content = preg_replace_callback('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/i', function ($match) {
                            return $this->download_img_callback($match);
                        }, $content);
                    }
                }
                return $content;
            }];
        }
        if ('utf-8' == $this->_config['sourcecharset']) {
            $obj = QueryList::get($url);
        } else {
            $obj = QueryList::get($url)->encoding('UTF-8')->removeHead();
        }
        $cont = $obj->rules($rules)->query()->getData()->all();
        QueryList::destructDocuments();
        return $cont;

    }

    /**
     * 转换图片地址为绝对路径，为下载做准备。
     * @param array $out 图片地址
     */
    protected function download_img_callback($matches)
    {
        return $this->download_img($matches[0], $matches[1]);
    }

    protected function download_img($html, $oldUrl)
    {
        if (!empty($html) && !empty($oldUrl)) {
            $newUrl = $url = '';
            if (false === strpos($oldUrl, '://')) {
                $newUrl = $url = $this->url_check($oldUrl, $this->_url);
            }
            if ($this->_config['down_attachment']) {
                $newUrl = $this->getUrlFile($url);
            }
            return str_replace($oldUrl, $newUrl, $html);
        } else {
            return $old;
        }
    }

    protected function getUrlFile($url)
    {
        $file_info = [
            'aid'    => $this->_uid,
            'module' => 'admin',
            'thumb'  => '',
        ];
        $host = parse_url($url, PHP_URL_HOST);
        if ($host != $_SERVER['HTTP_HOST']) {
            $fileExt = strrchr($url, '.');
            if (!in_array($fileExt, ['.jpg', '.gif', '.png', '.bmp', '.jpeg', '.tiff'])) {
                return $url;
            }
            //图片是否合法
            $imgInfo = getimagesize($url);
            if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                return $url;
            }
            $filename = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'temp' . DS . md5($url) . $fileExt;
            if (http_down($url, $filename) !== false) {
                $file_info['md5'] = hash_file('md5', $filename);
                if ($file_exists = Attachment::get(['md5' => $file_info['md5']])) {
                    unlink($filename);
                    $url = $file_exists['path'];
                } else {
                    $file_info['sha1'] = hash_file('sha1', $filename);
                    $file_info['size'] = filesize($filename);
                    $file_info['mime'] = mime_content_type($filename);

                    $fpath    = 'images' . DS . date('Ymd');
                    $savePath = ROOT_PATH . 'public' . DS . 'uploads' . DS . $fpath;
                    if (!is_dir($savePath)) {
                        mkdir($savePath, 0755, true);
                    }
                    $fname             = DS . md5(microtime(true)) . $fileExt;
                    $file_info['name'] = $url;
                    $file_info['path'] = config('public_url') . 'uploads/' . str_replace(DS, '/', $fpath . $fname);
                    $file_info['ext']  = ltrim($fileExt, ".");

                    if (rename($filename, $savePath . $fname)) {
                        Attachment::create($file_info);
                        $url = $file_info['path'];
                    }
                }
            }
        }

        return $url;
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
