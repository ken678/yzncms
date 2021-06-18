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
// | 后台登录背景图插件
// +----------------------------------------------------------------------
namespace addons\loginbg;

use sys\Addons;

class Loginbg extends Addons
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

    public function adminLoginStyle()
    {
        $config = $this->getAddonConfig();
        if ($config['mode'] == 'random' || $config['mode'] == 'daily') {
            $gettime     = $config['mode'] == 'random' ? mt_rand(-1, 7) : 0;
            $json_string = file_get_contents('https://www.bing.com/HPImageArchive.aspx?format=js&idx=' . $gettime . '&n=1');
            $data        = json_decode($json_string);
            $background  = "https://www.bing.com" . $data->{"images"}[0]->{"urlbase"} . "_1920x1080.jpg";
        } else {
            $background = $config['pic'];
        }
        $this->assign('background', $background);
        return $this->fetch('loginbg');
    }

}
