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
// | editormd插件
// +----------------------------------------------------------------------
namespace addons\editormd;

use think\Addons;

class Editormd extends Addons
{
    //插件信息
    public $info = [
        'name'        => 'editormd',
        'title'       => 'editormd编辑器',
        'description' => 'editormd编辑器',
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
    public function markdown($data)
    {
        $this->assign('vo', $data);
        return $this->fetch('editormd');
    }
}
