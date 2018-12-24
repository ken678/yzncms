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
// | 栏目模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use \think\Model;

/**
 * 模型
 */
class Category extends Model
{
    //新增栏目
    public function addCategory($data)
    {
        if (empty($data)) {
            $this->error = '添加栏目数据不能为空！';
            return false;
        }
        //栏目拼音
        /*$catname = iconv('utf-8', 'gbk', $data['catname']);
        $letters = gbk_to_pinyin($catname);
        $data['letter'] = strtolower(implode('', $letters));*/

        $catid = self::allowField(true)->save($data);
        if ($catid) {
            cache('Category', null);
            return $catid;
        } else {
            $this->error = '栏目添加失败！';
            return false;

        }
    }

    //刷新栏目索引缓存
    public function category_cache()
    {
        $data = self::order("listorder ASC")->select();
        $CategoryIds = array();
        foreach ($data as $r) {
            $CategoryIds[$r['id']] = array(
                'id' => $r['id'],
                'parentid' => $r['parentid'],
            );
        }
        cache("Category", $CategoryIds);
        return $CategoryIds;
    }

}
