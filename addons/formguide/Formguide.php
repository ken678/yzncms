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
// | 表单插件
// +----------------------------------------------------------------------
namespace addons\formguide;

use think\Addons;

class Formguide extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            "name"      => "admin/formguide.formguide/index",
            "status"    => 1,
            "title"     => "表单管理",
            "icon"      => "icon-file-list-3-line",
            "listorder" => 3,
            "child"     => [
                [
                    "name"   => "admin/formguide.formguide/add",
                    "status" => 0,
                    "title"  => "添加表单",
                ],
                [
                    "name"   => "admin/formguide.formguide/edit",
                    "status" => 0,
                    "title"  => "编辑表单",
                ],
                [
                    "name"   => "admin/formguide.formguide/del",
                    "status" => 0,
                    "title"  => "删除表单",
                ],
                [
                    "name"   => "admin/formguide.field/index",
                    "status" => 0,
                    "title"  => "字段管理",
                ],
                [
                    "name"   => "admin/formguide.field/add",
                    "status" => 0,
                    "title"  => "添加字段",
                ],
                [
                    "name"   => "admin/formguide.field/edit",
                    "status" => 0,
                    "title"  => "编辑字段",
                ],
                [
                    "name"   => "admin/formguide.field/del",
                    "status" => 0,
                    "title"  => "删除字段",
                ],
                [
                    "name"   => "admin/formguide.info/index",
                    "status" => 0,
                    "title"  => "信息列表",
                ],
                [
                    "name"   => "admin/formguide.info/public_view",
                    "status" => 0,
                    "title"  => "信息查看",
                ],
                [
                    "name"   => "admin/formguide.info/del",
                    "status" => 0,
                    "title"  => "信息删除",
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
        // 删除模型中建的表
        // $table_list = Db::name('model')->where(['module' => 'formguide'])->field('tablename,id')->select();
        // if ($table_list) {
        //     foreach ($table_list as $val) {
        //         $tablename = config('database.prefix') . $val['tablename'];
        //         Db::execute("DROP TABLE IF EXISTS `{$tablename}`;");

        //         Db::name('model_field')->where(['modelid' => $val['id']])->delete();
        //     }
        // }
        // //删除模型中的表
        // Db::name('model')->where(['module' => 'formguide'])->delete();
        return true;
    }

}