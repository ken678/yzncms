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

namespace app\formguide\model;

use app\content\model\ModelField;

/**
 * 模块模型
 * @package app\admin\model
 */
class FormguideField extends ModelField
{
    protected $name = 'model_field';

    /**
     * 根据模型ID，返回表名
     * @param type $modelid
     * @param type $modelid
     * @return string
     */
    protected function getModelTableName($modelid, $issystem)
    {
        //读取模型配置 以后优化缓存形式
        $model_cache = cache('Model_form');
        //表名获取
        $model_table = $model_cache[$modelid]['tablename'];
        return $model_table;
    }

}
