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

namespace app\member\model;

use think\Model;

/**
 * 模块模型
 * @package app\admin\model
 */
class MemberGroup extends Model
{
    //生成会员组缓存
    public function membergroup_cache()
    {
        $data = $this->select();
        if ($data) {
            $data = collection($data)->toArray();
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
            $return[$v['groupid']] = $v;
        }
        cache("Member_group", $return);
        return $return;
    }

}
