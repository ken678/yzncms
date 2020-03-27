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
// | 邮箱类
// +----------------------------------------------------------------------
namespace addons\saiyouems\lib;

class Ems
{
    private $_params = [];
    protected $error = '';
    protected $config = [];

    public function __construct($options = [])
    {
        if ($config = get_addon_config('saiyouems')) {
            $this->config = array_merge($this->config, $config);
        }
        $this->config = array_merge($this->config, is_array($options) ? $options : []);
    }

    /**
     * 立即发送邮件
     */
    public function send()
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $aGetParam = array(
            "appid" => $this->config['appid'], //用于调用access_token，接口获取授权后的access token
            "signature" => $this->config['appkey'], //申请应用时分配的AppSecret
            "from" => $this->config['from'], //发件人地址

            "to" => $this->_params['email'], //收件人地址
            "subject" => $this->_params['subject'], //邮件标题
            "text" => $this->_params['text'], //纯文本邮件正文
        );

        $response = $client->request('POST', 'https://api.mysubmail.com/mail/send', ['form_params' => $aGetParam]);
        if (200 == $response->getStatusCode()) {
            $res = json_decode($response->getBody()->getContents(), true);
            if (isset($res['status']) && $res['status'] == 'success') {
                return true;
            }
            $this->error = isset($res['msg']) ? $res['msg'] : '未知错误';
        } else {
            $this->error = $response->getReasonPhrase();
        }
        return false;
    }

    /**
     * 接收邮箱
     */
    public function email($email = '')
    {
        $this->_params['email'] = $email;
        return $this;
    }

    /**
     * 邮件标题
     */
    public function subject($subject = '')
    {
        $this->_params['subject'] = $subject;
        return $this;
    }

    /**
     * 邮件标题
     */
    public function text($text = '')
    {
        $this->_params['text'] = $text;
        return $this;
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->error;
    }

}
