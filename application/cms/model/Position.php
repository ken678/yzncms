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
// | 推荐模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use \think\Model;

/**
 * 模型
 */
class Position extends Model
{

    /**
     * 推荐位推送修改接口
     * 适合在文章发布、修改时调用
     * @param int $id 推荐文章ID
     * @param int $modelid 模型ID
     * @param array $posid 推送到的推荐位ID
     * @param int $expiration 过期时间设置
     * 调用方式
     * $push = D("Position");
     * $push->positionUpdate(323, 25, 45, array(20,21), array('title'=>'文章标题','thumb'=>'缩略图路径','inputtime'='时间戳'));
     */
    public function positionUpdate($id, $modelid, $catid, $posid, $expiration = 0)
    {
        $arr = array();
        $id = intval($id);
        if (empty($id)) {
            return false;
        }
        $modelid = intval($modelid);
        //组装属性参数
        $arr['id'] = $id;
        $arr['modelid'] = $modelid;
        $arr['catid'] = $catid;
        $arr['posid'] = $posid;
        return $this->position_list($arr, $expiration) ? true : false;
    }

    /**
     * 接口处理方法
     * @param array $arr 参数 表单数据，只在请求添加时传递。 例：array('modelid'=>1, 'catid'=>12);
     * @param int $expiration 过期时间设置
     * @param string $model 调取的数据库型名称
     */
    public function position_list($arr = array(), $expiration = 0)
    {
        if (is_array($arr['posid']) && !empty($arr['posid'])) {
            foreach ($arr['posid'] as $pid) {

            }

        }
        return true;
    }

    //推荐位缓存
    public function position_cache()
    {
        $data = self::order('id', 'DESC')->select();
        $data = $data ? $data->toArray() : array();
        $cache = array();
        foreach ($data as $rs) {
            $cache[$rs['id']] = $rs;
        }
        cache('Position', $cache);
        return $data;
    }

}
