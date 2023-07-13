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

use think\Db;
use think\Exception;
use think\Model;

/**
 * 用户组模型类
 * Class AuthGroupModel
 */
class AuthGroup extends Model
{
    const TYPE_ADMIN                = 1; // 管理员用户组类型标识
    const MEMBER                    = 'admin';
    const AUTH_EXTEND               = 'auth_extend'; // 动态权限扩展信息表
    const AUTH_GROUP                = 'auth_group'; // 用户组表名
    const AUTH_EXTEND_CATEGORY_TYPE = 1; // 分类权限标识
    const AUTH_EXTEND_MODEL_TYPE    = 2; //分类权限标识

    protected $resultSetType   = 'collection';
    protected static $roleList = [];

    /**
     * 根据角色Id获取角色名
     * @param int $Groupid 角色id
     * @return string 返回角色名
     */
    public function getRoleIdName($Groupid)
    {
        return $this->where(['id' => $Groupid])->value('title');
    }

    public static function init()
    {
        self::beforeDelete(function ($row) {
            if ($row->id == 1) {
                throw new Exception("超级管理员角色不能被删除！");
            }
            $admin = Db::name('Admin')->where('roleid', $row->id)->find();
            if ($admin) {
                throw new Exception("该角色下有管理员！");
            }
            //子角色列表
            $child = explode(',', self::getArrchildid($row->id));
            if (count($child) > 1) {
                throw new Exception("该角色下有子角色，请删除子角色才可以删除！");
            }
        });
    }

    /**
     * 通过递归的方式获取该角色下的全部子角色
     * @param type $id
     * @return string
     */
    public static function getArrchildid($id)
    {
        if (empty(self::$roleList)) {
            self::$roleList = self::order(["id" => "desc"])->column('*', 'id');
        }
        $arrchildid = $id;
        if (is_array(self::$roleList)) {
            foreach (self::$roleList as $k => $cat) {
                if ($cat['parentid'] && $k != $id && $cat['parentid'] == $id) {
                    $arrchildid .= ',' . self::getArrchildid($k);
                }
            }
        }
        return $arrchildid;
    }
}
