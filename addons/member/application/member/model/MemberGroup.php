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

use think\Model;

class MemberGroup extends Model
{
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
