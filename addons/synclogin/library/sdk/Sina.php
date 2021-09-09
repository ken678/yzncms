<?php
/**
 * 新浪SDK
 */
namespace addons\synclogin\library\sdk;

use addons\synclogin\library\Oauth;

class Sina extends Oauth
{
    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://api.weibo.com/oauth2/authorize';

    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://api.weibo.com/oauth2/access_token';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://api.weibo.com/2/';

    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api 微博API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false)
    {
        /* 新浪微博调用公共参数 */
        $params = array(
            'access_token' => $this->Token['access_token'],
        );
        $data = $this->http($this->url($api, '.json'), $this->param($params, $param), $method);
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
        $data = $this->call('users/show', "uid={$this->openid()}");
        if ($data['id']) {
            $userInfo['token']            = $this->Token ?? [];
            $userInfo['type']             = 'sina';
            $userInfo['name']             = $data['name'];
            $userInfo['nickname']         = $data['screen_name'];
            $userInfo['avatar']           = $data['avatar_large'];
            $userInfo['openid']           = $data['id'];
            $userInfo['idstr']            = $data['idstr'];
            $userInfo['province']         = $data['province'];
            $userInfo['city']             = $data['city'];
            $userInfo['location']         = $data['location'];
            $userInfo['created_at']       = $data['created_at'];
            $userInfo['gender']           = $data['gender'] == 'm' ? '男' : ($data['gender'] == 'f' ? '女' : '未知');
            $userInfo['followers_count']  = $data['followers_count'];
            $userInfo['friends_count']    = $data['friends_count'];
            $userInfo['statuses_count']   = $data['statuses_count'];
            $userInfo['favourites_count'] = $data['favourites_count'];
            $userInfo['description']      = $data['description'];
            $userInfo['url']              = $data['url'];
            $userInfo['profile_url']      = $data['profile_url'];
            return $userInfo;
        } else {
            throw new \Exception("获取新浪微博用户信息失败：{$data['error']}");
        }
    }

    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     */
    protected function parseToken($result, $extend)
    {
        $data = json_decode($result, true);
        if ($data['access_token'] && $data['expires_in'] && $data['remind_in'] && $data['uid']) {
            $this->Token    = $data;
            $data['openid'] = $data['uid'];
            unset($data['uid']);
            return $data;
        } else {
            throw new \Exception("获取新浪微博ACCESS_TOKEN出错：{$data['error']}");
        }

    }

    /**
     * 获取当前授权应用的openid
     * @return string
     */
    public function openid()
    {
        $data = $this->Token;
        if (isset($data['openid'])) {
            return $data['openid'];
        } else {
            throw new \Exception('没有获取到新浪微博用户ID！');
        }

    }
}
