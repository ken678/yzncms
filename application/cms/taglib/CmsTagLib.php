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
// | 标签库
// +----------------------------------------------------------------------
namespace app\cms\taglib;

use think\facade\Cache;

class CmsTagLib
{
    public $where;

    /**
     * 组合查询条件
     * @param type $attr
     * @return type
     */
    public function where($attr)
    {
        $where = [];
        if (isset($attr['where']) && $attr['where']) {
            array_push($where, $attr['where']);
        }
        //栏目id条件
        if (isset($attr['catid']) && (int) $attr['catid']) {
            $catid = (int) $attr['catid'];
            if (getCategory($catid, 'child')) {
                $catids_str = getCategory($catid, 'arrchildid');
                $pos = strpos($catids_str, ',') + 1;
                $catids_str = substr($catids_str, $pos);
                array_push($where, "catid in(" . $catids_str . ")");
            } else {
                array_push($where, "catid = " . $catid);
            }
        }
        $where_str = "";
        if (0 < count($where)) {
            $where_str = implode(" AND ", $where);
        }
        $this->where = $where_str;
        return $this->where;
    }

    /**
     * 栏目标签
     */
    public function Category($data)
    {

        $where = isset($data['where']) ? $data['where'] : "status=1";
        $order = isset($data['order']) ? $data['order'] : 'listorder,id desc';
        //每页显示总数
        $num = isset($data['num']) ? (int) $data['num'] : 10;
        if (isset($data['catid'])) {
            $catid = (int) $data['catid'];
            $where .= empty($where) ? "parentid = " . $catid : " AND parentid = " . $catid;
        }
        //如果条件不为空，进行查库
        if (!empty($where)) {
            $categorys = model('Category')->where($where)->limit($num)->order($data['order'])->select();

        }
        return $categorys;
    }

    /**
     * 列表标签
     */
    public function lists($data)
    {
        if (!$data['catid']) {
            return false;
        }
        $where = isset($data['where']) ? $data['where'] : "status=1";
        //当前栏目信息
        $catInfo = getCategory($data['catid']);
        //栏目所属模型
        $modelid = $catInfo['modelid'];
        $modelCache = cache("Model");
        $tableName = $modelCache[$modelid]['tablename'];
        $this->where($data);
        $list = \think\Db::name(ucwords($tableName))->where($this->where)->paginate(1);
        return $list;
    }

}
