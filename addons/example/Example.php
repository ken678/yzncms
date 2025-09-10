<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | 插件禁止分享、复制、转售、传播等任何形式的二次分发
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 示例插件
// +----------------------------------------------------------------------
namespace addons\example;

use app\common\library\Menu;
use think\Addons;

class Example extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'example',
                'title'   => '开发示例管理',
                'icon'    => 'iconfont icon-table-line',
                'sublist' => [
                    [
                        'name'    => 'example.fulltable',
                        'title'   => '表格完整示例',
                        'sublist' => [
                            ['name' => 'example.fulltable/index', 'title' => '查看'],
                            ['name' => 'example.fulltable/detail', 'title' => '详情'],
                            ['name' => 'example.fulltable/del', 'title' => '删除'],
                        ],
                    ],
                    [
                        'name'    => 'example.customform',
                        'title'   => '自定义表单示例',
                        'sublist' => [
                            ['name' => 'example.customform/index', 'title' => '查看'],
                        ],
                    ],
                    [
                        'name'    => 'example.tablelink',
                        'title'   => '表格联动示例',
                        'sublist' => [
                            ['name' => 'example.tablelink/index', 'title' => '查看'],
                            ['name' => 'example.tablelink/del', 'title' => '删除'],
                        ],
                    ],
                    [
                        'name'    => 'example.colorbadge',
                        'title'   => '彩色角标',
                        'sublist' => [
                            ['name' => 'example.colorbadge/index', 'title' => '查看'],
                            ['name' => 'example.colorbadge/del', 'title' => '删除'],
                        ],
                    ],
                    [
                        'name'    => 'example.tabletemplate',
                        'title'   => '表格模板示例',
                        'sublist' => [
                            ['name' => 'example.tabletemplate/index', 'title' => '查看'],
                            ['name' => 'example.tabletemplate/edit', 'title' => '编辑'],
                            ['name' => 'example.tabletemplate/del', 'title' => '删除'],
                        ],
                    ],
                    [
                        'name'    => 'example.echarts',
                        'title'   => '统计图表示例',
                        'sublist' => [
                            ['name' => 'example.echarts/index', 'title' => '查看'],
                        ],
                    ],
                ],
            ],
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('example');
        return true;
    }

    /**
     * 插件启用方法
     */
    public function enable()
    {
        Menu::enable('example');
    }

    /**
     * 插件禁用方法
     */
    public function disable()
    {
        Menu::disable('example');
    }

}
