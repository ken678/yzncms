<?php
/**
 * Gitee SDK
 */
namespace addons\synclogin\library\sdk;

use addons\synclogin\library\Oauth;

class Gitee extends Oauth
{
    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://gitee.com/oauth/authorize';

    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://gitee.com/oauth/token';

    /**
     * 获取request_code的额外参数,可在配置中修改 URL查询字符串格式
     * @var srting
     */
    protected $Authorize = 'scope=user_info';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://gitee.com/api/v5/';

    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api Gitee API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false)
    {
        /* Gitee 调用公共参数 */
        $params = array();
        $header = array("Authorization: bearer {$this->Token['access_token']}");
        $data   = $this->http($this->url($api), $this->param($params, $param), $method, $header);
        return json_decode($data, true);
    }

    /**
     * 获取用户信息
     * @return array
     */
    public function getUserInfo($params = [])
    {
        $params = $params ? $params : $_GET;
        if (!$this->Token) {
            $this->getAccessToken($params['code']);
        }
        $data = $this->call('user');
        if (isset($data['id'])) {
            $userInfo['token']    = $this->Token ?? [];
            $userInfo['type']     = 'gitee';
            $userInfo['name']     = $data['name'];
            $userInfo['nickname'] = $data['login'];
            $userInfo['avatar']   = $data['avatar_url'];
            $userInfo['openid']   = $data['id'];
            $userInfo['html_url'] = $data['html_url'];
            $userInfo['blog']     = $data['blog'];
            $userInfo['email']    = $data['email'];
            return $userInfo;
        } else {
            throw new \Exception("获取Gitee用户信息失败");
        }
    }

    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     */
    protected function parseToken($result, $extend)
    {
        $data = json_decode($result, true);
        if ($data['access_token'] && $data['token_type']) {
            $this->Token    = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else {
            throw new \Exception("获取 Gitee ACCESS_TOKEN出错：未知错误");
        }

    }

    /**
     * 获取当前授权应用的openid
     * @return string
     */
    public function openid()
    {
        $data = $this->call('user');
        if (isset($data['id'])) {
            return $data['id'];
        } else {
            throw new \Exception('没有获取到 Gitee 用户ID！');
        }

    }
}
