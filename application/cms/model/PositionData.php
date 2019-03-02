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
// | 推荐数据模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use \think\Model;

/**
 * 模型
 */
class PositionData extends Model
{
    //关联删除需设置主键
    protected $pk = 'posid';

    /**
     * 信息从推荐位中移除
     * @param type $posid 推荐位id
     * @param type $id 信息id
     * @param type $modelid] 模型id
     */
    public function deleteItem($posid, $id, $modelid)
    {
        if (!$posid || !$id || !$modelid) {
            return false;
        }
        $where = array();
        $where['id'] = $id;
        $where['modelid'] = $modelid;
        $where['posid'] = intval($posid);
        if (self::where($where)->delete() !== false) {
            $this->contentPos($id, $modelid);
            //删除相关联的附件
            //service('Attachment')->api_delete('position-' . $modelid . '-' . $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新信息推荐位状态
     * @param type $id 信息id
     * @param type $modelid 模型id
     */
    public function contentPos($id, $modelid)
    {
        $id = intval($id);
        $modelid = intval($modelid);
        $info = self::where(array('id' => $id, 'modelid' => $modelid))->find();
        if ($info) {
            $posids = 1;
        } else {
            $posids = 0;
        }
        $model_cache = cache("Model");
        $model_table = $model_cache[$modelid]['tablename'];
        $rs = \think\Db::name($model_table)->where('id', $id)->update(['posid' => $posids]);
        if ($rs) {
            return true;
        } else {
            return false;
        }

    }

}
