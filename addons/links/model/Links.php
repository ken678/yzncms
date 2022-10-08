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
namespace addons\links\model;

use think\Db;
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
        $count = Db::name('Terms')->where(["name" => $name, "module" => "links"])->count();
        if ($count > 0) {
            throw new \Exception("该分类已经存在！");
        }
        return Db::name('Terms')->insertGetId(["name" => $name, "module" => "links"]);
    }

    /**
     * 获取友情链接
     * @param $tag
     */
    public function getList($tag)
    {
        $tag['order'] = !empty($tag['order']) ? $tag['order'] : 'listorder,id desc';
        $cacheTime    = !empty($tag['cache']) && is_numeric($tag['cache']) ? intval($tag['cache']) : 3600;
        //每页显示总数
        $num = isset($tag['num']) && intval($tag['num']) > 0 ? intval($tag['num']) : 10;

        $cacheID = to_guid_string($tag);
        if ($cacheData = cache($cacheID)) {
            return $cacheData;
        }
        $map = [['status', '=', 1]];
        if (isset($tag['linktype'])) {
            $map[] = ['linktype', '=', (int) $tag['linktype']];
        }
        if (isset($tag['typeid'])) {
            $map[] = ['termsid', '=', (int) $tag['typeid']];
        }
        $obj = $this->where($map)->where($tag['where'])->order($tag['order'])->limit($num)->select();
        if ($obj->isEmpty()) {
            return [];
        }
        $array = $obj->toArray();
        cache($cacheID, $array, $cacheTime);
        return $array;

    }

}
