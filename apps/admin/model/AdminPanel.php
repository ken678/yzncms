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
 * 快捷菜单模型
 */
class AdminPanel extends Model
{
    protected $resultSetType = 'collection';
    /**
     * 添加快捷菜单
     * @param type $data
     * @return boolean
     */
    public function createPanel($data)
    {
        $info = $this->where(array("menuid" => $data['menuid']))->find();
        if (!empty($info->data) || empty($data)) {
            return true;
        }
        return $this->save($data) !== false ? true : false;
    }

    /**
     * 删除快捷菜单
     * @param type $data
     * @return boolean
     */
    public function deletePanel($data)
    {
        if (empty($data['menuid']) || empty($data['userid'])) {
            return false;
        }
        $info = $this->where($data)->find();
        if (empty($info->data) || empty($data)) {
            return true;
        }
        return $this->where($data)->delete() !== false ? true : false;
    }

    /**
     * 返回某个用户的全部常用菜单
     * @param type $userid 用户ID
     * @return type
     */
    public function getAllPanel($userid)
    {
        $info['main'] = $this->where(array('userid' => $userid))->select()->toarray();
        if (empty($info['main'])) {
            $info['ids'] = array();
            return $info;
        }
        foreach ($info['main'] as $key => $v) {
            $info['ids'][] = $v['menuid'];
        }
        return $info;
    }

}
