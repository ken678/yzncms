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
namespace app\content\model;

use think\Db;
use think\Model;

/**
 * 推荐位模型
 */
class Position extends Model
{
    /**
     * 添加推荐位
     * @param type $data 数据
     * @return boolean
     */
    public function positionAdd($data)
    {
        if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
        $data['modelid'] = is_array($data['modelid']) ? implode(',', $data['modelid']) : 0;
        $data['catid'] = is_array($data['catid']) ? implode(',', $data['catid']) : 0;
        $posid = $this->allowField(true)->save($data);
        if ($posid) {
            $this->position_cache();
            return $posid;
        } else {
            $this->error = '添加失败！';
            return false;
        }
    }

    /**
     * 更新推荐位
     * @param type $data 数据
     * @return boolean
     */
    public function positionSave($data)
    {
        if (empty($data) || empty($data['posid'])) {
            $this->error = '没有数据！';
            return false;
        } else {
            $posid = $data['posid'];
            unset($data['posid']);
        }
        $data['modelid'] = is_array($data['modelid']) ? implode(',', $data['modelid']) : 0;
        $data['catid'] = is_array($data['catid']) ? implode(',', $data['catid']) : 0;

        if ($this->allowField(true)->save($data, ['posid' => $posid]) !== false) {
            $this->position_cache();
            return true;
        } else {
            $this->error = '更新失败！';
            return false;
        }

    }

    //推荐位缓存
    public function position_cache()
    {
        $data = Db::name('Position')->order(array('posid' => 'DESC'))->select();
        $data = $data ?: array();
        $cache = array();
        foreach ($data as $rs) {
            $cache[$rs['posid']] = $rs;
        }
        cache('Position', $cache);
        return $data;
    }

}
