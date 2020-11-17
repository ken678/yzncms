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
// | 返回顶部插件
// +----------------------------------------------------------------------
namespace addons\returntop;

use think\Addons;

class ReturnTop extends Addons
{
    //插件信息
    public $info = [
        'name'        => 'returntop',
        'title'       => '返回顶部',
        'description' => '回到顶部美化，随机或指定显示，100款样式，每天一种换，天天都用新样式',
        'status'      => 1,
        'author'      => '御宅男',
        'version'     => '1.0.0',
    ];

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
     * 编辑器挂载的文章内容钩子
     * @param array('name'=>'表单name','value'=>'表单对应的值')
     */
    public function pageFooter($data)
    {
        $this->assign('addons_data', $data);
        $config = $this->getAddonConfig();
        if ($config['random']) {
            $config['current'] = rand(1, 99);
        }
        $this->assign('addons_config', $config);
        return $this->fetch('content');
    }
}
