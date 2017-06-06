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
class AuthGroup extends Model {
    const TYPE_ADMIN                = 1;                   // 管理员用户组类型标识
    const MEMBER                    = 'admin';
    const AUTH_EXTEND               = 'auth_extend';       // 动态权限扩展信息表
    const AUTH_GROUP                = 'auth_group';        // 用户组表名
    const AUTH_EXTEND_CATEGORY_TYPE = 1;              // 分类权限标识
    const AUTH_EXTEND_MODEL_TYPE    = 2; //分类权限标识

    protected $resultSetType = 'collection';

    /**
     * 返回用户组列表
     * 默认返回正常状态的管理员用户组列表
     * @param array $where   查询条件,供where()方法使用
     */
    public function getGroups($where=array()){
        $map = array('status'=>1,'type'=>self::TYPE_ADMIN,'module'=>'admin');
        $map = array_merge($map,$where);
        return $this->where($map)->select()->toArray();
    }

    /**
     * 返回用户所属用户组信息
     * @param  int    $uid 用户id
     * @return array  用户所属的用户组 array(
     * array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     * ...)
     */
    static public function getUserGroup($uid){
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        $prefix = config('database.prefix');
        $user_groups = \think\Db::table($prefix.self::MEMBER)
			        ->alias('a')
			        ->field('userid,roleid,title,description,rules')
			        ->join($prefix.self::AUTH_GROUP." g"," g.id=a.roleid")
			        ->where("a.userid='$uid' and g.status='1'")
			        ->select();
        $groups[$uid]=$user_groups?$user_groups:array();
        return $groups[$uid];
    }


    /**
     * 根据角色Id获取角色名
     * @param int $roleId 角色id
     * @return string 返回角色名
     */
    public function getRoleIdName($roleId) {
        return $this->where(array('id' => $roleId))->value('title');
    }


}

