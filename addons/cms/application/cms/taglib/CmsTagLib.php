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

use app\cms\model\Category as Category_Model;
use think\Db;

class CmsTagLib
{

    /**
     * 组合查询条件
     *
     * @param  type  $attr
     *
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
                $pos        = strpos($catids_str, ',') + 1;
                $catids_str = substr($catids_str, $pos);
                array_push($where, "`catid` in(" . $catids_str . ',' . $catid . ")");
            } else {
                array_push($where, "`catid` = " . $catid);
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
        $url_mode = isset(cache("Cms_Config")['site_url_mode']) ? cache("Cms_Config")['site_url_mode'] : 1;
        $where    = isset($data['where']) ? $data['where'] : "`status`=1";
        $order    = isset($data['order']) ? $data['order'] : 'listorder DESC,id DESC';
        //每页显示总数
        //$num = isset($data['num']) ? (int) $data['num'] : 10;
        if (!isset($data['limit'])) {
            $data['limit'] = 0 == (int) $data['num'] ? 10 : (int) $data['num'];
        }
        if (isset($data['catid'])) {
            $catid = (int) $data['catid'];
            $where .= empty($where) ? "`parentid` = " . $catid : " AND `parentid` = " . $catid;
        }
        $categorys = Category_Model::where($where)->limit($data['limit'])->order($data['order'])->select();
        if (!empty($categorys)) {
            foreach ($categorys as &$vo) {
                $cat       = $url_mode == 1 ? $vo['id'] : $vo['catdir'];
                $vo['url'] = buildCatUrl($cat, $vo['url']);
            }
        }
        return $categorys;
    }

    /**
     * 列表标签
     */
    public function lists($data)
    {
        $catid = isset($data['catid']) ? trim($data['catid']) : '';

        $data['where'] = isset($data['where']) ? $data['where'] . " AND `status`=1" : "`status`=1";
        if (!isset($data['limit'])) {
            $data['limit'] = 0 == (int) $data['num'] ? 10 : (int) $data['num'];
        }
        if (empty($data['order'])) {
            $data['order'] = ['updatetime' => 'DESC', 'id' => 'DESC'];
        }
        if (isset($data['flag'])) {
            $flag = [];
            foreach (explode(',', $data['flag']) as $k => $v) {
                $flag[] = "FIND_IN_SET('" . $v . "', flag)";
            }
            if ($flag) {
                $data['where'] .= " AND (" . implode(' OR ', $flag) . ")";
            }
        }
        $data['field']  = isset($data['field']) ? $data['field'] : '*';
        $data['simple'] = isset($data['simple']) ? (is_numeric($data['simple']) ? (int) $data['simple'] : (bool) $data['simple']) : false;
        $moreifo        = isset($data['moreinfo']) ? $data['moreinfo'] : 0;

        //如果设置了catid，则根据catid判断modelid,传入的modelid失效
        if ($catid) {
            //当前栏目信息
            $catInfo = getCategory($catid);
            $modelid = $catInfo ? $catInfo['modelid'] : 0;
        } else {
            if (!isset($data['modelid'])) {
                return false;
            }
            $modelid = intval($data['modelid']);
        }
        $pageconfig                                     = [];
        isset($data['pagepath']) && $pageconfig['path'] = $data['pagepath'];
        $result                                         = model('cms/Cms')->getList($modelid, $this->where($data), $moreifo, $data['field'], $data['order'], $data['limit'], $data['page'], $data['simple'], $pageconfig);
        return $result;
    }

    /**
     * Tags标签
     */
    public function tags($data)
    {
        $where = [];
        if (isset($attr['where']) && $attr['where']) {
            array_push($where, $attr['where']);
        }
        if (isset($data['tagid'])) {
            if (strpos($data['tagid'], ',') !== false) {
                $r = Db::name('Tags')->where('id', 'in', $data['tagid'])->value('tagid,tag');
                array_push($where, "tag in(" . $r . ")");
            } else {
                $r = Db::name('Tags')->where(['id' => (int) $data['tagid']])->find();
                array_push($where, "tag = '" . $r['tag'] . "'");
            }
        } else {
            if (is_array($data['tag'])) {
                array_push($where, "tag in(" . $data['tag'] . ")");
            } else {
                $tags = strpos($data['tag'], ',') !== false ? explode(',', $data['tag']) : explode(' ', $data['tag']);
                if (count($tags) == 1) {
                    array_push($where, "tag = '" . $data['tag'] . "'");
                } else {
                    array_push($where, "tag in('" . implode("', '", $tags) . "')");
                }
            }
        }
        $where_str = "";
        if (0 < count($where)) {
            $where_str = implode(" AND ", $where);
        }
        if (!isset($data['limit'])) {
            $data['limit'] = 0 == (int) $data['num'] ? 10 : (int) $data['num'];
        }
        $res = Db::name('TagsContent')->where($where_str)->limit($data['limit'])->select();
        //读取文章信息
        foreach ($res as $k => $v) {
            $r = model('cms/Cms')->getContent($v['modelid'], "`id` = " . $v['contentid'], false, '*', $data['limit'], $data['page']);
            if ($r) {
                $return[$k] = array_merge($v, $r);
            }
        }
        return $return;
    }

    /**
     * 上一页
     */
    public function pre($data)
    {
        //当没有内容时的提示语
        $msg = !empty($data['msg']) ? $data['msg'] : '已经没有了';
        //是否新窗口打开
        $target = !empty($data['target']) ? ' target=_blank ' : '';
        $result = model('cms/Cms')->getContent(getCategory($data['catid'], 'modelid'), "`catid` = " . $data['catid'] . " AND `status` = 1 AND `id` < " . $data['id'], false, '*', "id desc");
        if (!$result) {
            $result['title'] = $msg;
            $result['url']   = 'javascript:alert("' . $msg . '");';
        }
        $result['target'] = $target;
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
        $target = !empty($data['target']) ? ' target=_blank ' : '';
        $result = model('cms/Cms')->getContent(getCategory($data['catid'], 'modelid'), "`catid` = " . $data['catid'] . " AND `status` = 1 AND `id` > " . $data['id'], false, '*');
        if (!$result) {
            $result['title'] = $msg;
            $result['url']   = 'javascript:alert("' . $msg . '");';
        }
        $result['target'] = $target;
        return $result;
    }

}
