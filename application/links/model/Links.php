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
// | Links模型
// +----------------------------------------------------------------------
namespace app\links\model;

use think\Model;

/**
 * 模型
 */
class Links extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime         = 'inputtime';

    protected static function init()
    {
        self::beforeWrite(function ($row) {
            if (isset($row['termsname']) && $row['termsname']) {
                $row['termsid'] = self::addTerms(trim($row['termsname']));
            }
        });
    }

    /**
     * 添加分类
     * @param type $name
     */
    protected static function addTerms($name)
    {
        $count = \think\Db::name('Terms')->where(["name" => $name, "module" => "links"])->count();
        if ($count > 0) {
            throw new \Exception("该分类已经存在！");
        }
        return \think\Db::name('Terms')->insertGetId(["name" => $name, "module" => "links"]);
    }

}
