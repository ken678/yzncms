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
namespace app\content\taglib;

use Cache;
use think\Db;

class Content
{
    public $db, $table_name, $modelid, $where;

    /**
     * 组合查询条件
     * @param type $attr
     * @return type
     */
    public function where($attr)
    {
        $where = array();
        //设置SQL where 部分
        if (isset($attr['where']) && $attr['where']) {
            $where['_string'] = $attr['where'];
        }
        //栏目id条件
        if (isset($attr['catid']) && (int) $attr['catid']) {
            $catid = (int) $attr['catid'];
            if (getCategory($catid, 'child')) {
                $catids_str = getCategory($catid, 'arrchildid');
                $pos = strpos($catids_str, ',') + 1;
                $catids_str = substr($catids_str, $pos);
                $where['catid'] = array("IN", $catids_str);
            } else {
                $where['catid'] = array("EQ", $catid);
            }
        }
        //缩略图
        if (isset($attr['thumb'])) {
            if ($attr['thumb']) {
                $where['thumb'] = array("NEQ", "");
            } else {
                $where['thumb'] = array("EQ", "");
            }
        }
        //审核状态
        $where['status'] = array("EQ", 99);
        $this->where = $where;
        return $this->where;
    }

    /**
     * 初始化模型
     * @param $catid
     */
    public function set_modelid($catid = 0, $isModelid = false)
    {
        if ($catid && !$isModelid) {
            if (getCategory($catid, 'type') && getCategory($catid, 'type') != 0) {
                return false;
            }
            $this->modelid = getCategory($catid, 'modelid');
        } else {
            $this->modelid = $catid;
        }
        return $this->db = Db::name(get_table_name($this->modelid));
    }

    /**
     * 统计
     */
    public function count($data)
    {
        if ($data['action'] == 'lists') {
            if (!$this->set_modelid($data['catid'])) {
                return false;
            }
            return $this->db->where($this->where($data))->count();
        }
    }

    /**
     * 内容列表（lists）
     * 参数名   是否必须    默认值     说明
     * catid     否   null    调用栏目ID
     * where     否   null    sql语句的where部分
     * thumb     否   0   是否仅必须缩略图
     * order     否   null    排序类型
     * num   是   null    数据调用数量
     * moreinfo  否   0   是否调用副表数据 1为是
     *
     * moreinfo参数属性，本参数表示在返回数据的时候，会把副表中的数据也一起返回。一个内容模型分为2个表，一个主表一个副表，主表中一般是保存了标题、所属栏目等等短小的数据（方便用于索引），而副表则保存了大字段的数据，如内容等数据。在模型管理中新建字段的时候，是允许你选择存入到主表还是副表的（我们推荐的是，把不重要的信息放到副表中）。
     * @param $data
     */
    public function lists($data)
    {
        //缓存时间
        $cache = (int) $data['cache'];
        $cacheID = to_guid_string($data);
        if ($cache && $return = Cache::get($cacheID)) {
            return $return;
        }
        if (!$data['catid']) {
            return false;
        }
        if (!$this->set_modelid($data['catid'])) {
            return false;
        }
        $this->where($data);
        //判断是否启用分页，如果没启用分页则显示指定条数的内容
        if (!isset($data['limit'])) {
            $data['limit'] = (int) $data['num'] == 0 ? 10 : (int) $data['num'];
        }
        //排序
        if (empty($data['order'])) {
            $data['order'] = array('updatetime' => 'DESC', 'id' => 'DESC');
        }
        $return = $this->db->where($this->where)->limit($data['limit'])->order($data['order'])->select();

        //调用副表的数据
        if (isset($data['moreinfo']) && intval($data['moreinfo']) == 1) {
            $ids = array();
            foreach ($return as $v) {
                if (isset($v['id']) && !empty($v['id'])) {
                    $ids[] = $v['id'];
                } else {
                    continue;
                }
            }
            if (!empty($ids)) {
                $r = Db::name($this->table_name . '_data')->where('id', 'in', $ids)->select();
                if (!empty($r)) {
                    foreach ($r as $k => $v) {
                        if (isset($return[$k])) {
                            $return[$k] = array_merge($v, $return[$k]);
                        }

                    }
                }
            }
        }
        //结果进行缓存
        if ($cache) {
            Cache::set($cacheID, $return, $cache);
        }
        return $return;

    }

    /**
     * 栏目列表
     */
    public function category($data)
    {
        //缓存时间
        $cache = (int) $data['cache'];
        //调用数量
        $num = (int) $data['num'];
        $cacheID = to_guid_string($data);
        if ($cache && $array = Cache::get($cacheID)) {
            return $array;
        }
        //栏目ID
        $data['catid'] = intval($data['catid']);
        $where = "";
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            $where = $data['where'];
        }
        $db = Db::name('Category');
        if (isset($data['catid'])) {
            //$where['ismenu'] = 1;
            //$where['parentid'] = $data['catid'];
            if ($where) {
                $where .= " AND `ismenu` = 1 AND `parentid` = '" . intval($data['catid']) . "'";
            } else {
                $where .= " `ismenu` = 1 AND `parentid` = '" . intval($data['catid']) . "'";
            }
        }
        //如果条件不为空，进行查库
        if (!empty($where)) {
            if ($num) {
                $categorys = $db->where($where)->limit($num)->order($data['order'])->select();
            } else {
                $categorys = $db->where($where)->order($data['order'])->select();
            }
        }
        //结果进行缓存
        if ($cache) {
            Cache::set($cacheID, $categorys, $cache);
        }
        return $categorys;
    }

}
