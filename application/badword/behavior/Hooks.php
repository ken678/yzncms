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
// | 过滤词钩子
// +----------------------------------------------------------------------
namespace app\badword\behavior;

use think\Db;

class Hooks
{

    //或者run方法
    public function BadwordReplace($content)
    {
        $res = Db::name('Badword')->column('badword,replaceword');
        return str_replace(array_keys($res), array_values($res), $content);
    }

}
