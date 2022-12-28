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
// | 快递查询插件
// +----------------------------------------------------------------------
namespace addons\expressquery;

use Finecho\Logistics\Logistics;
use think\Addons;
use think\Exception;
use think\Loader;

class Expressquery extends Addons
{
    //安装
    public function install()
    {
        return true;
    }

    //卸载
    public function uninstall()
    {
        return true;
    }

    /**
     * 添加命名空间
     */
    public function appInit()
    {
        Loader::addNamespace('Finecho\\Logistics', ADDON_PATH . 'expressquery' . DS . 'SDK' . DS . 'src' . DS);
    }

    //物流信息查询
    public function expressQuery($data)
    {
        if (!isset($data['express_id'])) {
            throw new Exception("快递单号不能为空");
        }
        $addonconfig = $this->getAddonConfig();
        $config      = [
            'provider' => $addonconfig['provider'],
        ];
        $config[$addonconfig['provider']] = $addonconfig['config'];

        try {
            $order     = [];
            $logistics = new Logistics($config);
            $order     = $logistics->query($data['express_id'], $data['express_name'] ?? '');
        } catch (\Exception $e) {
            //throw new Exception("发生错误：" . $e->getMessage());
        }
        return $order;
    }
}
