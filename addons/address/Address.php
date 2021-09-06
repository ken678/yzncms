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
// | 地图位置插件
// +----------------------------------------------------------------------
namespace addons\address;

use sys\Addons;

class Address extends Addons
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

    public function showMap($params)
    {
        $config = $this->getAddonConfig();
        if (!empty($config['appkey'])) {
            $params['appkey']     = $config['appkey'];
            $params['width']      = isset($params['width']) ? $params['width'] : 800;
            $params['height']     = isset($params['height']) ? $params['height'] : 500;
            $params['point']      = isset($params['point']) ? $params['point'] : 18;
            $params['title']      = isset($params['title']) ? $params['title'] : '某某网络科技有限公司';
            $params['content']    = isset($params['content']) ? $params['content'] : '公司地址：江苏省苏州市吴中区某某工业园一区<br/>手机：158-88888888<br/>邮箱：admin@admin.com';
            $params['coordinate'] = (isset($params['coordinate']) && false !== strpos($params['coordinate'], ',')) ? explode(",", $params['coordinate']) : ['116.403874', '39.914889'];
            $this->assign('params', $params);
            return $this->fetch('map');
        } else {
            echo '<b>未设置百度地图秘钥，请先现在插件后台设置！</b>';
        }

    }

}
