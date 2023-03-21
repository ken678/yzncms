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
// | 神马操作类
// +----------------------------------------------------------------------
namespace addons\weburlpush\library\push\driver;

use addons\weburlpush\library\push\Driver;
use GuzzleHttp\Exception\ClientException;
use think\Exception;

class Shenma extends Driver
{
    public function push($urls)
    {
        return $this->request($urls);
    }

    protected function request($urls)
    {
        $url = "https://data.zhanzhang.sm.cn/push?site={$this->config['site']}&user_name={$this->config['user_name']}&resource_name=mip_add&token={$this->config['token']}";
        try {
            $response = $this->getHttpClient()->post($url, [
                'headers' => ['Content-Type' => 'text/plain'],
                'body'    => implode("\n", $urls),
            ]);
            $body = json_decode($response->getBody(), true);
            if ($body['returnCode'] !== 200) {
                throw new Exception($body['errorMsg']);
            }
            return $body;
        } catch (ClientException $e) {
            $body = json_decode($e->getResponse()->getBody(), true);
            throw new Exception($body['errorMsg']);
        }
    }
}
