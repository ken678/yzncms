<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 百度推送插件
// +----------------------------------------------------------------------
namespace addons\baidupush;

use addons\baidupush\library\Push;
use think\Addons;

class Baidupush extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"      => "admin/baidupush.admin/index",
            "status"    => 1,
            "title"     => "百度推送",
            "tip"       => "",
            "listorder" => 0,
            "child"     => [
                [
                    "name"      => "admin/baidupush.admin/xiongzhang",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "熊掌号推送",
                    "tip"       => "",
                    "listorder" => 0,
                ],
                [
                    "name"      => "admin/baidupush.admin/zhanzhang",
                    "data"      => "",
                    "status"    => 0,
                    "title"     => "百度站长推送",
                    "tip"       => "",
                    "listorder" => 0,
                ],
            ],
        ],

    );

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

    public function baidupush($params, $extra = null)
    {
        $config = $this->getAddonConfig();
        $urls   = is_string($params) ? [$params] : $params;
        $extra  = $extra ? $extra : 'urls';
        $status = explode(',', $config['status']);
        foreach ($status as $index => $item) {
            if ($extra == 'urls' || $extra == 'append') {
                Push::connect(['type' => $item])->realtime($urls);
            } elseif ($extra == 'del' || $extra == 'delete') {
                Push::connect(['type' => $item])->delete($urls);
            }
        }
    }

}
