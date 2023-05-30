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
// | 内容管理插件
// +----------------------------------------------------------------------
namespace addons\cms;

use addons\cms\library\FulltextSearch;
use think\Addons;
use think\Db;
use think\facade\Config;
use think\facade\Route;

class Cms extends Addons
{
    protected $ext_table = '_data';
    //后台菜单
    public $admin_list = array(
        [
            //父菜单ID，NULL或者不写系统默认，0为顶级菜单
            "parentid"  => 0,
            "name"      => "admin/cms.cms/index",
            "status"    => 1,
            "title"     => "内容",
            "icon"      => "icon-draft-line",
            "listorder" => 0,
            "child"     => [
                [
                    "name"   => "admin/cms.cms/index2",
                    "status" => 1,
                    "title"  => "内容管理",
                    "icon"   => "icon-draft-line",
                    "child"  => [
                        [
                            "name"   => "admin/cms.cms/index",
                            "status" => 1,
                            "title"  => "管理内容",
                            "icon"   => "icon-draft-line",
                            "child"  => [
                                [
                                    "name"   => "admin/cms.cms/panl",
                                    "status" => 0,
                                    "title"  => "面板",
                                ],
                                [
                                    "name"   => "admin/cms.cms/classlist",
                                    "status" => 0,
                                    "title"  => "信息列表",
                                ],
                                [
                                    "name"   => "admin/cms.cms/add",
                                    "status" => 0,
                                    "title"  => "添加",
                                ],
                                [
                                    "name"   => "admin/cms.cms/edit",
                                    "status" => 0,
                                    "title"  => "编辑",
                                ],
                                [
                                    "name"   => "admin/cms.cms/del",
                                    "status" => 0,
                                    "title"  => "删除",
                                ],
                                [
                                    "name"   => "admin/cms.cms/listorder",
                                    "status" => 0,
                                    "title"  => "排序",
                                ],
                                [
                                    "name"   => "admin/cms.cms/remove",
                                    "status" => 0,
                                    "title"  => "批量移动",
                                ],
                                [
                                    "name"   => "admin/cms.cms/setstate",
                                    "status" => 0,
                                    "title"  => "状态",
                                ],
                                [
                                    "name"   => "admin/cms.cms/check_title",
                                    "status" => 0,
                                    "title"  => "标题检查",
                                ],
                                [
                                    "name"   => "admin/cms.cms/recycle",
                                    "status" => 0,
                                    "title"  => "回收站",
                                    "icon"   => "icon-trash",
                                ],
                                [
                                    "name"   => "admin/cms.cms/destroy",
                                    "status" => 0,
                                    "title"  => "清空回收站",
                                ],
                                [
                                    "name"   => "admin/cms.cms/restore",
                                    "status" => 0,
                                    "title"  => "还原回收站",
                                ],
                            ],
                        ],
                        [
                            "name"   => "admin/cms.publish/index",
                            "status" => 1,
                            "title"  => "稿件管理",
                            "icon"   => "icon-draft-line",
                            "child"  => [
                                [
                                    "name"   => "admin/cms.publish/del",
                                    "status" => 0,
                                    "title"  => "删除",
                                ],
                                [
                                    "name"   => "admin/cms.publish/pass",
                                    "status" => 0,
                                    "title"  => "通过",
                                ],
                                [
                                    "name"   => "admin/cms.publish/reject",
                                    "status" => 0,
                                    "title"  => "退稿",
                                ],
                            ],
                        ],
                        [
                            "name"   => "admin/cms.order/index",
                            "status" => 1,
                            "title"  => "订单管理",
                            "icon"   => "icon-file-list-3-line",
                            "child"  => [
                                [
                                    "name"   => "admin/cms.order/del",
                                    "status" => 0,
                                    "title"  => "删除",
                                ],
                            ],
                        ],
                        [
                            "name"   => "admin/cms.tags/index",
                            "status" => 1,
                            "title"  => "Tags管理",
                            "icon"   => "icon-label",
                            "child"  => [
                                [
                                    "name"   => "admin/cms.tags/index",
                                    "status" => 0,
                                    "title"  => "列表",
                                ],
                                [
                                    "name"   => "admin/cms.tags/add",
                                    "status" => 0,
                                    "title"  => "添加",
                                ],
                                [
                                    "name"   => "admin/cms.tags/edit",
                                    "status" => 0,
                                    "title"  => "编辑",
                                ],
                                [
                                    "name"   => "admin/cms.tags/del",
                                    "status" => 0,
                                    "title"  => "删除",
                                ],
                                [
                                    "name"   => "admin/cms.tags/create",
                                    "status" => 0,
                                    "title"  => "数据重建",
                                ],
                                [
                                    "name"   => "admin/cms.tags/multi",
                                    "status" => 0,
                                    "title"  => "批量更新",
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    "name"   => "admin/cms.category/index1",
                    "type"   => 1,
                    "status" => 1,
                    "title"  => "相关设置",
                    "icon"   => "icon-file-settings-line",
                    "child"  => [
                        [
                            "name"   => "admin/cms.category/index",
                            "status" => 1,
                            "title"  => "栏目列表",
                            "icon"   => "icon-other",
                            "child"  => [
                                [
                                    "name"   => "admin/cms.category/add",
                                    "status" => 0,
                                    "title"  => "添加栏目",
                                ],
                                [
                                    "name"   => "admin/cms.category/cat_priv",
                                    "status" => 0,
                                    "title"  => "栏目授权",
                                ],
                                [
                                    "name"   => "admin/cms.category/edit",
                                    "status" => 0,
                                    "title"  => "编辑栏目",
                                ],
                                [
                                    "name"   => "admin/cms.category/del",
                                    "status" => 0,
                                    "title"  => "删除栏目",
                                ],
                                [
                                    "name"   => "admin/cms.category/multi",
                                    "status" => 0,
                                    "title"  => "批量更新",
                                ],
                                [
                                    "name"   => "admin/cms.category/public_tpl_file_list",
                                    "status" => 0,
                                    "title"  => "栏目模板",
                                ],
                            ],
                        ],
                        [
                            "name"   => "admin/cms.models/index",
                            "status" => 1,
                            "title"  => "模型管理",
                            "icon"   => "icon-apartment",
                            "child"  => [
                                [
                                    "name"   => "admin/cms.field/index",
                                    "status" => 0,
                                    "title"  => "字段管理",
                                ],
                                [
                                    "name"   => "admin/cms.field/add",
                                    "status" => 0,
                                    "title"  => "字段添加",
                                ],
                                [
                                    "name"   => "admin/cms.field/edit",
                                    "status" => 0,
                                    "title"  => "字段编辑",
                                ],
                                [
                                    "name"   => "admin/cms.field/del",
                                    "status" => 0,
                                    "title"  => "字段删除",
                                ],
                                [
                                    "name"   => "admin/cms.field/listorder",
                                    "status" => 0,
                                    "title"  => "字段排序",
                                ],
                                [
                                    "name"   => "admin/cms.field/setstate",
                                    "status" => 0,
                                    "title"  => "字段状态",
                                ],
                                [
                                    "name"   => "admin/cms.field/setsearch",
                                    "status" => 0,
                                    "title"  => "字段搜索",
                                ],
                                [
                                    "name"   => "admin/cms.field/setvisible",
                                    "status" => 0,
                                    "title"  => "字段隐藏",
                                ],
                                [
                                    "name"   => "admin/cms.field/setrequire",
                                    "status" => 0,
                                    "title"  => "字段必须",
                                ],

                                [
                                    "name"   => "admin/cms.models/add",
                                    "status" => 0,
                                    "title"  => "添加模型",
                                ],
                                [
                                    "name"   => "admin/cms.models/edit",
                                    "status" => 0,
                                    "title"  => "修改模型",
                                ],
                                [
                                    "name"   => "admin/cms.models/del",
                                    "status" => 0,
                                    "title"  => "删除模型",
                                ],
                                [
                                    "name"   => "admin/cms.models/multi",
                                    "status" => 0,
                                    "title"  => "批量更新",
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],

    );

    public $cache_list = array(
        'Category' => [
            'name'   => '栏目索引',
            'model'  => 'Category',
            'action' => 'category_cache',
        ],
    );

    //安装
    public function install()
    {
        $info = get_addon_info('member');
        if (!$info || $info['status'] != 1) {
            throw new \think\Exception("请在后台插件管理中安装《会员插件》并启用后再尝试");
        }
        return true;
    }

    //卸载
    public function uninstall()
    {
        $droptables = request()->param("droptables");
        $auth       = \app\admin\service\User::instance();
        //只有开启调试且为超级管理员才允许删除相关数据库
        if ($droptables && Config::get("app_debug") && $auth->isAdministrator()) {
            // 删除模型中建的表
            $table_list = Db::name('model')->where('module', 'cms')->field('tablename,type,id')->select();
            if ($table_list) {
                foreach ($table_list as $val) {
                    $tablename = Config::get('database.prefix') . $val['tablename'];
                    Db::execute("DROP TABLE IF EXISTS `{$tablename}`;");
                    if ($val['type'] == 2) {
                        Db::execute("DROP TABLE IF EXISTS `{$tablename}{$this->ext_table}`;");
                    }
                    Db::name('model_field')->where('modelid', $val['id'])->delete();
                }
            }
            //删除模型中的表
            Db::name('model')->where(['module' => 'cms'])->delete();
        }
        return true;
    }

    //或者run方法
    public function userSidenavAfter($content)
    {
        return $this->fetch('userSidenavAfter');
    }

    public function xunsearchIndexReset($project)
    {
        if ($project['name'] == 'cms') {
            return FulltextSearch::reset();
        }
    }

    public function appInit()
    {
        $config = get_addon_config('cms');

        Route::rule('', 'cms/index/index');
        Route::rule('index', 'cms/index/index');
        Route::rule('lists/:catid', 'cms/index/lists')->pattern(['catid' => '\d+']);
        Route::rule('shows/:catid/:id', 'cms/index/shows')->pattern(['catid' => '\d+', 'id' => '\d+']);
        Route::rule('tag/[:tag]', 'cms/index/tags');
        Route::rule('search', 'cms/index/search');
        if ($config['site_url_mode'] == 2) {
            //Route::rule('admin', 'admin/index/login'); //如去除c/ d/ 需要解开此注释
            Route::rule('d/:catdir/:id', 'cms/index/shows')->pattern(['catdir' => '[A-Za-z0-9\-\_]+', 'id' => '\d+']);
            Route::rule('c/:catdir/[:condition]', 'cms/index/lists')->pattern(['catdir' => '[A-Za-z0-9\-\_]+', 'condition' => '[0-9_&=a-zA-Z]+']);
        }

        //此函数需要全局调用
        if (is_file(ADDON_PATH . 'cms' . DS . 'function.php')) {
            include_once ADDON_PATH . 'cms' . DS . 'function.php';
        }
    }

}
