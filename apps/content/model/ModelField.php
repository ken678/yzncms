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
namespace app\content\model;
use think\Db;
use think\Model;

/**
 * 菜单基础模型
 */
class ModelField extends Model
{
    //生成模型字段缓存
    public function model_field_cache() {
        $cache = array();
        $modelList = Db::name("Model")->select();
        foreach ($modelList as $info) {
            $data = Db::name("ModelField")->where(array("modelid" => $info['modelid'], "disabled" => 0))->order(" listorder ASC ")->select();
            $fieldList = array();
            if (!empty($data) && is_array($data)) {
                foreach ($data as $rs) {
                    $fieldList[$rs['field']] = $rs;
                }
            }
            $cache[$info['modelid']] = $fieldList;
        }
        cache('ModelField', $cache);
        return $cache;
    }


}