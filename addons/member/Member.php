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
// | 会员插件
// +----------------------------------------------------------------------
namespace addons\member;

use think\Addons;

class Member extends Addons
{
    //后台菜单
    public $admin_list = array(
        [
            //父菜单ID，NULL或者不写系统默认，0为顶级菜单
            "parentid"  => 0,
            "name"      => "admin/member.member/index2",
            "status"    => 1,
            "title"     => "会员",
            "icon"      => "icon-user-line",
            "listorder" => 4,
            "child"     => [
                [
                    "name"   => "admin/member.member/index1",
                    "status" => 1,
                    "title"  => "会员管理",
                    "icon"   => "icon-user-line",
                    "child"  => [
                        [
                            "name"   => "admin/member.member/index",
                            "status" => 1,
                            "title"  => "会员管理",
                            "icon"   => "icon-user-shared-2-line",
                            "child"  => [
                                [
                                    "name"   => "admin/member.member/add",
                                    "status" => 0,
                                    "title"  => "会员添加",
                                ],
                                [
                                    "name"   => "admin/member.member/edit",
                                    "status" => 0,
                                    "title"  => "会员编辑",
                                ],
                                [
                                    "name"   => "admin/member.member/del",
                                    "status" => 0,
                                    "title"  => "会员删除",
                                ],
                                [
                                    "name"   => "admin/member.member/pass",
                                    "status" => 0,
                                    "title"  => "会员审核",
                                ],
                            ],
                        ],
                        [
                            "name"   => "admin/member.member/userverify",
                            "status" => 1,
                            "title"  => "审核会员",
                            "icon"   => "icon-user-star-line",
                        ],
                    ],
                ],
                [
                    "name"   => "admin/member.group/index1",
                    "status" => 1,
                    "title"  => "会员组",
                    "icon"   => "icon-group-line",
                    "child"  => [
                        [
                            "name"   => "admin/member.group/index",
                            "status" => 1,
                            "title"  => "会员组管理",
                            "icon"   => "icon-user-settings-line",
                            "child"  => [
                                [
                                    "name"   => "admin/member.group/add",
                                    "status" => 0,
                                    "title"  => "会员组添加",
                                ],
                                [
                                    "name"   => "admin/member.group/edit",
                                    "status" => 0,
                                    "title"  => "会员组编辑",
                                ],
                                [
                                    "name"   => "admin/member.group/del",
                                    "status" => 0,
                                    "title"  => "会员组删除",
                                ],
                            ],
                        ],
                    ],
                ],

            ],
        ],
    );

    public $cache_list = array(
        'Member_Group' => [
            'name'   => '会员组',
            'model'  => 'MemberGroup',
            'action' => 'membergroup_cache',
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

    public function contentDeleteEnd($params)
    {
        //参数是审核文章的数据
        if (!empty($params) && isset($params['sysadd']) && $params['sysadd'] == 0) {
            //删除对应的会员投稿记录信息
            db("member_content")->where(array("content_id" => $params['id'], "catid" => $params['catid']))->delete();
        }
    }

    public function contentEditEnd($params)
    {
        //参数是审核文章的数据
        if (!empty($params)) {
            //标识审核状态
            db("member_content")->where(["content_id" => $params['id'], "catid" => $params['catid']])->setField('status', $params['status']);
        }
    }
}
