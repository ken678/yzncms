<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

/**
 * 用户组模型类
 * Class AuthGroupModel
 */
class AuthGroup extends Model
{
    const TYPE_ADMIN = 1; // 管理员用户组类型标识
    const MEMBER = 'admin';
    const AUTH_EXTEND = 'auth_extend'; // 动态权限扩展信息表
    const AUTH_GROUP = 'auth_group'; // 用户组表名
    const AUTH_EXTEND_CATEGORY_TYPE = 1; // 分类权限标识
    const AUTH_EXTEND_MODEL_TYPE = 2; //分类权限标识

    protected $resultSetType = 'collection';

    /**
     * 返回用户组列表
     * 默认返回正常状态的管理员用户组列表
     * @param array $where   查询条件,供where()方法使用
     */
    public function getGroups($where = array())
    {
        $map = array('status' => 1, 'type' => self::TYPE_ADMIN, 'module' => 'admin');
        $map = array_merge($map, $where);
        return $this->where($map)->select();
    }

    /**
     * 根据角色Id获取角色名
     * @param int $roleId 角色id
     * @return string 返回角色名
     */
    public function getRoleIdName($roleId)
    {
        return $this->where(array('id' => $roleId))->value('title');
    }

}
