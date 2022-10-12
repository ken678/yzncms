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
// | cms全文搜索类
// +----------------------------------------------------------------------
namespace app\cms\library;

use addons\xunsearch\library\Xunsearch;
use think\Db;

class FulltextSearch
{

    //重置搜索索引数据库
    public static function reset()
    {
        $models   = array_values(cache('Model'));
        $category = Db::name('category')->where('type', 2)->column('modelid', 'id');
        if (count($models) > 0) {
            foreach ($models as $k => $v) {
                if ($v['type'] == 1) {
                    //独立表
                    Db::name($v['tablename'])->where('status', 1)->chunk(100, function ($list) use ($category) {
                        foreach ($list as $val) {
                            self::update(($category[$val['catid']] ?? 0), $val['catid'], $val['id'], $val);
                        }
                    });
                } elseif ($v['type'] == 2) {
                    Db::view($v['tablename'], '*')
                        ->where('status', 1)->view($v['tablename'] . '_data', '*', $v['tablename'] . '.id=' . $v['tablename'] . '_data' . '.did', 'LEFT')
                        ->chunk(100, function ($list) use ($category) {
                            foreach ($list as $val) {
                                self::update(($category[$val['catid']] ?? 0), $val['catid'], $val['id'], $val, $val);
                            }
                        });
                }
            }
        }
        return true;
    }

    /**
     * 新增/更新索引
     */
    public static function update($modelid, $catid, $id, $data, $dataExt)
    {
        $res              = [];
        $res['pid']       = $catid . '_' . $id;
        $res['catid']     = $catid;
        $res['id']        = $id;
        $res['modelid']   = $modelid;
        $res['title']     = isset($data['title']) ? $data['title'] : '';
        $res['content']   = isset($dataExt['content']) ? strip_tags(htmlspecialchars_decode($dataExt['content'])) : '';
        $res['inputtime'] = isset($data['inputtime']) ? $data['inputtime'] : 0;
        $res['url']       = buildContentUrl($catid, $id);
        Xunsearch::instance('cms')->update($res);
    }

    /**
     * 删除
     */
    public static function del($catid, $id)
    {
        Xunsearch::instance('cms')->del($catid . '_' . $id);
    }

    public static function setQuery($query, $fulltext = true, $fuzzy = false, $synonyms = false)
    {
        return Xunsearch::instance('cms')->setQuery($query, $fulltext, $fuzzy, $synonyms);
    }

    /**
     * 获取搜索结果
     */
    public static function search($page = 1, $pagesize = 20, $order = '', $query)
    {
        return Xunsearch::instance('cms')->search($page, $pagesize, $order, $query);
    }

    /**
     * 获取搜索热门关键字
     */
    public static function hot()
    {
        return Xunsearch::instance('cms')->getXS()->search->getHotQuery();
    }
}
