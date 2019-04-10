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
// | 会员组模型
// +----------------------------------------------------------------------
namespace app\member\model;

use \think\Model;

/**
 * 模型
 */
class MemberGroup extends Model
{
    protected $auto = ['issystem' => 0, 'allowvisit' => 1, 'status' => 1];

    /**
     * 添加会员组
     * @param type $data 提交数据
     * @return boolean
     */
    public function groupAdd($data)
    {
        if (!is_array($data)) {
            return false;
        }
        $group = self::create($data, true);
        return $group;
    }

    /**
     * 删除用户组
     * @param type $groupid 用户组ID，可以是数组
     * @return boolean
     */
    public function groupDelete($groupid)
    {
        if (empty($groupid)) {
            $this->error = '没有指定需要删除的会员组别！';
            return false;
        }

        $info = self::get($groupid);
        if ($info['issystem']) {
            $this->error = '系统用户组[' . $info['name'] . ']不能删除！';
            return false;
        }
        if (false !== $info->delete()) {
            return true;
        } else {
            $this->error = '删除失败！';
            return false;
        }
    }

    //生成会员组缓存
    public function membergroup_cache()
    {
        $data = $this->select();
        if ($data) {
            $data = $data->toArray();
        } else {
            return;
        }
        $return = array();
        foreach ($data as $k => $v) {
            if ($v['expand']) {
                $v['expand'] = unserialize($v['expand']);
            } else {
                $v['expand'] = array();
            }
            $return[$v['id']] = $v;
        }
        cache("Member_Group", $return);
        return $return;
    }

}
