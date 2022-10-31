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

use think\Model;

/**
 * 模型
 */
class Category extends Model
{

    //刷新栏目索引缓存
    public function category_cache()
    {
        $data        = self::order("listorder DESC")->select();
        $CategoryIds = array();
        foreach ($data as $r) {
            $CategoryIds[$r['id']] = array(
                'id'       => $r['id'],
                'catdir'   => $r['catdir'],
                'parentid' => $r['parentid'],
            );
        }
        cache("Category", $CategoryIds);
        return $CategoryIds;
    }
}
