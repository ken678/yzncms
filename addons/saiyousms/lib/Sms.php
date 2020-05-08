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
// | 短信类
// +----------------------------------------------------------------------
namespace addons\saiyousms\lib;

class Sms
{
    private $_params = [];
    protected $error = '';
    protected $config = [];

    public function __construct($options = [])
    {
        if ($config = get_addon_config('saiyousms')) {
            $this->config = array_merge($this->config, $config);
        }
        $this->config = array_merge($this->config, is_array($options) ? $options : []);
    }

    /**
     * 立即发送短信
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send()
    {
        $this->error = '';
        $postArr = array(
            "appid" => $this->config['appid'], //用于调用access_token，接口获取授权后的access token
            "signature" => $this->config['appkey'], //申请应用时分配的AppSecret
            'to' => $this->_params['mobile'],
            "content" => $this->_params['msg'],
        );
        $options = [
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8',
            ),
        ];
        $result = \util\Http::sendRequest('https://api.mysubmail.com/message/send', json_encode($postArr), 'POST', $options);
        if ($result['ret']) {
            $res = (array) json_decode($result['msg'], true);
            if (isset($res['status']) && $res['status'] == 'success') {
                return true;
            }

            $this->error = isset($res['msg']) ? $res['msg'] : 'InvalidResult';
        } else {
            $this->error = $result['msg'];
        }
        return false;
    }

    /**
     * 接收手机
     * @param string $mobile 手机号码
     * @return $this
     */
    public function mobile($mobile = '')
    {
        $this->_params['mobile'] = $mobile;
        return $this;
    }

    /**
     * 短信内容
     * @param string $msg 短信内容
     * @return $this
     */
    public function msg($msg = '')
    {
        $this->_params['msg'] = $msg;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

}
