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
// | 聚合推送插件
// +----------------------------------------------------------------------
namespace addons\weburlpush;

use addons\weburlpush\library\Push;
use think\Addons;

class Weburlpush extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"   => "admin/weburlpush.admin/index",
            "status" => 1,
            "title"  => "百度推送",
            "child"  => [
                [
                    "name"   => "admin/weburlpush.admin/baidu",
                    "status" => 0,
                    "title"  => "熊掌号推送",
                ],
                [
                    "name"   => "admin/weburlpush.admin/shenma",
                    "status" => 0,
                    "title"  => "百度站长推送",
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

    public function weburlpush(Push $push, $params, $extra = null)
    {
        $config = $this->getAddonConfig();
        $urls   = is_string($params) ? [$params] : $params;
        $extra  = $extra ? $extra : 'push';
        $status = explode(',', $config['status']);
        try {
            foreach ($status as $index => $item) {
                if ($extra == 'push') {
                    $push->channel($item)->push($urls);
                }
            }
        } catch (\Exception $e) {
            //$this->error("推送失败：" . $e->getMessage());
        }
    }

}
