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
        $arr     = [];
        switch ($config['type']) {
            case 'baidu':
                $max    = $config['max'];
                $client = new AipNlp($config['appid'], $config['apikey'], $config['secretkey']);
                $result = $client->lexer($content);
                if (isset($result['items'])) {
                    $max = count($result['items']) > $max ? $max : count($result['items']);
                    foreach ($result['items'] as $k => $v) {
                        if (in_array($v['pos'], ['n', 'nr', 'nt', 'nw']) && $v['byte_length'] > 2) {
                            $arr[] = $v['item'];
                        }
                    }
                    $arr = array_slice(array_unique($arr), 0, $max);
                    $arr = implode(',', $arr);
                }
                break;
            case 'xfyun':
                $max       = $config['max'];
                $XAppid    = $config['appid'];
                $Apikey    = $config['apikey'];
                $XCurTime  = time();
                $XParam    = "";
                $XCheckSum = "";
                $Param     = array(
                    "type" => "dependent",
                );
                $Post = array(
                    'text' => $content,
                );
                $XParam    = base64_encode(json_encode($Param));
                $XCheckSum = md5($Apikey . $XCurTime . $XParam);

                $options = [
                    CURLOPT_HTTPHEADER => array(
                        'X-CurTime:' . $XCurTime,
                        'X-Param:' . $XParam,
                        'X-Appid:' . $XAppid,
                        'X-CheckSum:' . $XCheckSum,
                        'Content-Type:application/x-www-form-urlencoded; charset=utf-8',
                    ),
                ];
                $result = \util\Http::sendRequest('http://ltpapi.xfyun.cn/v1/ke', http_build_query($Post), 'POST', $options);
                if ($result['ret']) {
                    $res = (array) json_decode($result['msg'], true);
                    if ($res['code'] == 0) {
                        $max = count($res['data']['ke']) > $max ? $max : count($res['data']['ke']);
                        foreach ($res['data']['ke'] as $t) {
                            $arr[] = $t['word'];
                        }
                        $arr = array_slice(array_unique($arr), 0, $max);
                        $arr = implode(',', $arr);
                    }
                }
                break;
        }
        $arr = empty($arr) ? '' : $arr;
        return json(["code" => 0, "arr" => $arr]);
    }
}
