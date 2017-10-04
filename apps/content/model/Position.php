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

    /**
     * 删除推荐位
     * @param type $posid 推荐位ID
     * @return boolean
     */
    public function positionDel($posid)
    {
        if (empty($posid)) {
            $this->error = '请指定需要删除的推荐位！';
            return false;
        }
        if (self::destroy(['posid' => $posid]) !== false) {
            $d = Db::name('PositionData')->where(array("posid" => $posid))->select();
            //$Attachment = service('Attachment');
            foreach ($d as $k => $v) {
                Db::name('PositionData')->where(array("posid" => $v['posid'], "id" => $v['id']))->delete();
                $this->content_pos($v['id'], $v['modelid']);
                //$Attachment->api_delete('position-' . $v['modelid'] . '-' . $v['id']);
            }
            $this->position_cache();
            return true;
        } else {
            $this->error = '删除失败！';
            return false;
        }
    }

    /**
     * 推荐位删除计算
     * Enter description here ...
     * @param int $catid 栏目ID
     * @param int $id 文章id
     * @param array $input_posid 传入推荐位数组
     */
    private function position_del($catid, $id, $input_posid)
    {
        $array = array();
        $pos_data = Db::name('PositionData');
        //查找已存在推荐位
        $olPosid = $pos_data->where(array('id' => $id, 'catid' => $catid))->column('posid');
        if (empty($olPosid)) {
            return false;
        }
        //差集计算，需要删除的推荐
        $real_posid = array_diff($olPosid, $input_posid);
        if (empty($real_posid)) {
            return false;
        }
        $where = array();
        $where['catid'] = array('EQ', $catid);
        $where['modelid'] = getCategory($catid, 'modelid');
        $where['id'] = array('EQ', $id);
        $where['posid'] = array('IN', $real_posid);
        $status = $pos_data->where($where)->delete();
        /*if (false !== $status) {
    service('Attachment')->api_delete('position-' . $where['modelid'] . '-' . $where['id']);
    return true;
    } else {
    return false;
    }*/
    }

    /**
     * 推荐位推送修改接口
     * 适合在文章发布、修改时调用
     * @param int $id 推荐文章ID
     * @param int $modelid 模型ID
     * @param array $posid 推送到的推荐位ID
     * @param array $data 推送数据
     * @param int $expiration 过期时间设置
     * @param int $undel 是否判断推荐位去除情况
     * @param string $model 调取的数据模型
     * 调用方式
     * $push = D("Position");
     * $push->positionUpdate(323, 25, 45, array(20,21), array('title'=>'文章标题','thumb'=>'缩略图路径','inputtime'='时间戳'));
     */
    public function positionUpdate($id, $modelid, $catid, $posid, $data, $expiration = 0, $undel = 0, $model = 'content')
    {
        $arr = $param = array();
        $id = intval($id);
        if (empty($id)) {
            return false;
        }
        $modelid = intval($modelid);
        $data['inputtime'] = $data['inputtime'] ? $data['inputtime'] : time();

        //组装属性参数
        $arr['modelid'] = $modelid;
        $arr['catid'] = $catid;
        $arr['posid'] = $posid;
        $arr['dosubmit'] = '1';

        //组装数据
        $param[0] = $data;
        $param[0]['id'] = $id;
        if ($undel == 0) {
            $pos_info = $this->position_del($catid, $id, $posid);
        }
        return $this->position_list($param, $arr, $expiration, $model) ? true : false;
    }

    /**
     * 接口处理方法
     * @param array $param 属性 请求时，为模型、栏目数组。提交添加为二维信息数据 。例：array(1=>array('title'=>'多发发送方法', ....))
     * @param array $arr 参数 表单数据，只在请求添加时传递。 例：array('modelid'=>1, 'catid'=>12);
     * @param int $expiration 过期时间设置
     * @param string $model 调取的数据库型名称
     */
    public function position_list($param = array(), $arr = array(), $expiration = 0, $model = 'content')
    {
        if ($arr['dosubmit']) {
            $pos_data = Db::name('PositionData');
            $position_info = cache('Position');
            $modelid = intval($arr['modelid']);
            $catid = intval($arr['catid']);
            $info = $r = array();
            $ModelField = cache('ModelField');
            $fulltext_array = $ModelField[$modelid];
            if (is_array($arr['posid']) && !empty($arr['posid']) && is_array($param) && !empty($param)) {
                foreach ($arr['posid'] as $pid) {
                    foreach ($param as $d) {
                        $info['id'] = $info['listorder'] = $d['id'];
                        $info['catid'] = $catid;
                        $info['posid'] = $pid;
                        $info['module'] = $model;
                        $info['modelid'] = $modelid;
                        foreach ($fulltext_array as $value) {
                            $field = $value['field'];
                            //判断字段是否入库到推荐位字段
                            if ($value['isposition']) {
                                if ($d[$field]) {
                                    $info['data'][$field] = $d[$field];
                                }

                            }
                        }
                        //颜色选择为隐藏域 在这里进行取值
                        $info['data']['style'] = $d['style'];
                        $info['thumb'] = $info['data']['thumb'] ? 1 : 0;
                        $info['data'] = serialize($info['data']);
                        $info['expiration'] = $expiration;

                        //判断推荐位数据是否存在，不存在新增
                        $r = $pos_data->where(array('id' => $d['id'], 'posid' => $pid, 'catid' => $info['catid']))->find();
                        if ($r) {
                            //是否同步编辑
                            if ($r['synedit'] == '0') {
                                //同步时，不从新设置排序值
                                unset($info['listorder']);
                                //更新推荐位
                                $pos_data->where(array('id' => $d['id'], 'posid' => $pid, 'catid' => $info['catid']))->update($info);
                            }
                        } else {
                            //插入推荐位
                            $status = $pos_data->insert($info);
                            if ($status !== false) {
                                $this->content_pos($info['id'], $info['modelid']);
                            }
                        }
                        unset($info);
                    }
                    //删除超出最大存储数据量
                    $maxnum = (int) $position_info[$pid]['maxnum'];
                    $r = $pos_data->where(array('posid' => $pid))->order("listorder DESC, id DESC")->limit($maxnum . ",100")->select();
                    if ($r && $position_info[$pid]['maxnum']) {
                        foreach ($r as $k => $v) {
                            $pos_data->where(array('id' => $v['id'], 'posid' => $v['posid'], 'catid' => $v['catid']))->delete();
                            //service('Attachment')->api_delete('position-' . $v['modelid'] . '-' . $v['id']);
                            $this->content_pos($v['id'], $v['modelid']);
                        }
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断文章是否被推荐，同时更新推荐状态
     * @param $id
     * @param $modelid
     */
    private function content_pos($id, $modelid)
    {
        $id = intval($id);
        $modelid = intval($modelid);
        if ($id && $modelid) {
            $db_data = Db::name("PositionData");
            $MODEL = cache("Model");
            $db_content = Db::name(ucwords($MODEL[$modelid]['tablename']));
            $posids = $db_data->where(array('id' => $id, 'modelid' => $modelid))->find() ? 1 : 0;
            //更新推荐状态
            $db_content->where(array('id' => $id))->update(array('posid' => $posids));
        }
        return $posids;
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
