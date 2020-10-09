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
// | 中文分词管理
// +----------------------------------------------------------------------
namespace addons\getwords\Controller;

use addons\getwords\library\AipNlp;
use app\addons\util\Adminaddon;

class Admin extends Adminaddon
{
    //获取内容关键字
    public function getkeywords()
    {
        $content = $this->request->param('content/s', '');
        $config  = get_addon_config('getwords');
        $max     = $config['max'];
        $client  = new AipNlp($config['appid'], $config['apikey'], $config['secretkey']);
        $result  = $client->lexer($content);
        $arr     = [];
        if (isset($result['items'])) {
            $max = count($result['items']) > $max ? $max : count($result['items']);
            foreach ($result['items'] as $k => $v) {
                if (in_array($v['pos'], ['n', 'nr', 'nt', 'nw']) && $v['byte_length'] > 2) {
                    $arr[] = $v['item'];
                }
            }
            $arr = array_slice(array_unique($arr), 0, $max);
            $arr = implode(',', $arr);
        } else {
            $arr = "";
        }
        return json(["code" => 0, "arr" => $arr]);
    }
}
