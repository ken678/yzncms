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

    /**
     * 组合查询条件
     * @param type $attr
     * @return type
     */
    protected function where($attr)
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
        return $where_str;
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
        $categorys = model('Category')->where($where)->limit($num)->order($data['order'])->select();
        if (!empty($categorys)) {
            foreach ($categorys as &$vo) {
                $vo['url'] = buildCatUrl($vo['type'], $vo['id'], $vo['url']);
            }
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
        $data['where'] = isset($data['where']) ? $data['where'] : "status=1";
        if (!isset($data['limit'])) {
            $data['limit'] = 0 == (int) $data['num'] ? 10 : (int) $data['num'];
        }
        if (empty($data['order'])) {
            $data['order'] = array('updatetime' => 'DESC', 'id' => 'DESC');
        }
        if (isset($data['flag'])) {
            $data['where'] = "FIND_IN_SET('" . intval($data['flag']) . "',flag)";
        }
        //当前栏目信息
        $catInfo = getCategory($data['catid']);
        //栏目所属模型
        $modelid = $catInfo['modelid'];
        $result = model('Cms')->getDataList($modelid, $this->where($data), $data['moreifo'], $data['field'], $data['order'], $data['limit'], $data['page']);
        return $result;
    }

    /**
     * 上一页
     */
    public function pre($data)
    {
        //当没有内容时的提示语
        $msg = !empty($data['msg']) ? $data['msg'] : '已经没有了';
        //是否新窗口打开
        $target = !empty($data['blank']) ? ' target="_blank" ' : ' target="_self" ';
        $result = model('Cms')->getDataInfo(getCategory($data['catid'], 'modelid'), "catid =" . $data['catid'] . " AND id <" . $data['id'], false, 'catid,id,title');
        if (!$result) {
            $result['title'] = $msg;
            $result['url'] = 'javascript:alert("' . $msg . '");';
            $result['blank'] = $target;
        } else {
            $result['blank'] = $target;
        }
        return $result;

    }

    /**
     * 下一页
     */
    public function next($data)
    {
        //当没有内容时的提示语
        $msg = !empty($data['msg']) ? $data['msg'] : '已经没有了';
        //是否新窗口打开
        $target = !empty($data['blank']) ? ' target=\"_blank\" ' : '';
        $result = model('Cms')->getDataInfo(getCategory($data['catid'], 'modelid'), "catid =" . $data['catid'] . " AND id >" . $data['id'], false, 'catid,id,title');
        if (!$result) {
            $result['title'] = $msg;
            $result['url'] = 'javascript:alert("' . $msg . '");';
            $result['blank'] = $target;
        } else {
            $result['blank'] = $target;
        }
        return $result;

    }

}
