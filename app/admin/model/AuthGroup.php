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

// +----------------------------------------------------------------------
// | 用户组模型类
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Exception;
use think\Model;

class AuthGroup extends Model
{
    protected static $roleList = [];

    public static function onBeforeDelete($row)
    {
        if ($row->id == 1) {
            throw new Exception("超级管理员角色不能被删除！");
        }
        $admin = AdminUser::where('roleid', $row->id)->find();
        if ($admin) {
            throw new Exception("该角色下有管理员！");
        }
        //子角色列表
        $child = explode(',', self::getArrchildid($row->id));
        if (count($child) > 1) {
            throw new Exception("该角色下有子角色，请删除子角色才可以删除！");
        }
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
