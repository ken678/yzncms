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
// | 短信插件
// +----------------------------------------------------------------------
namespace addons\easysms;

use sys\Addons;

class Easysms extends Addons
{
    public $config = [
        // HTTP 请求的超时时间（秒）
        'timeout'  => 5.0,

        // 默认发送配置
        'default'  => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                //阿里云
                'aliyun',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => APP_PATH . 'runtime/tmp/easy-sms.log',
            ],
            //阿里云
            'aliyun'   => [
                'access_key_id'     => '',
                'access_key_secret' => '',
                'sign_name'         => '',
            ],
        ],
    ];

    protected function init()
    {
        $config                                        = $this->getAddonConfig();
        $this->config['default']['gateways']           = (array) $config['gateways'];
        $this->config['gateways'][$config['gateways']] = (array) $config['config'];
    }

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 短信发送行为
     * @param   Sms     $params
     * @return  boolean
     */
    public function smsSend($params)
    {
        $this->init();
        $config  = $this->getAddonConfig();
        $easySms = new \Overtrue\EasySms\EasySms($this->config);
        try {
            $result = $easySms->send($params->mobile, [
                'content'  => '您的验证码为: ' . $params->code,
                'template' => isset($config['template'][$params->event]) ? $config['template'][$params->event] : 0,
                'data'     => [
                    'code' => $params->code,
                ],
            ]);
        } catch (\Exception $e) {
            //var_dump($e->getResults());
            return false;
        }
        $is_suc = false;
        foreach ($result as $v) {
            if ($v['status'] == 'success') {
                $is_suc = true;
                break;
            }
        }
        return $is_suc;
    }

    /**
     * 短信发送通知
     * @param   array   $params
     * @return  boolean
     */
    public function smsNotice($params)
    {
        $this->init();
        $easySms = new \Overtrue\EasySms\EasySms($this->config);
        try {
            $result = $easySms->send($params['mobile'], [
                'content'  => $params['msg'],
                'template' => $params['template'],
                'data'     => isset($params['data']) ? $params['data'] : [],
            ]);
        } catch (\Exception $exception) {
            return false;
        }
        $is_suc = false;
        foreach ($result as $v) {
            if ($v['status'] == 'success') {
                $is_suc = true;
                break;
            }
        }
        return $is_suc;
    }

    /**
     * 检测验证是否正确
     * @param   Sms     $params
     * @return  boolean
     */
    public function smsCheck($params)
    {
        return true;
    }

}
