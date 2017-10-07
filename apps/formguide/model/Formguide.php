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

use app\content\model\Models;
use think\Config;
use think\Model;

/**
 * 模块模型
 * @package app\admin\model
 */
class Formguide extends Models
{
    protected $name = 'model';
    //模型类型
    protected $modelType = 3;

    /**
     * 创建表单数据表
     * @param type $tableName 模型主表名称（不包含表前缀）
     * @param type $modelId 所属模型id
     * @return boolean
     */
    public function addModelFormguide($tableName, $modelId = false)
    {
        if (empty($tableName)) {
            return false;
        }
        $sql = "CREATE TABLE IF NOT EXISTS `think_form_table` (
                        `dataid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                        `userid` mediumint(8) unsigned NOT NULL,
                       `username` varchar(20) NOT NULL,
                       `datetime` int(10) unsigned NOT NULL,
                       `ip` char(15) NOT NULL,
                       PRIMARY KEY (`dataid`)
                    ) ENGINE=MyISAM;";
        //表名替换
        $sql = str_replace("think_form_table", Config::get("database.prefix") . $tableName, $sql);
        return $this->sql_execute($sql);
    }

    //新增表单
    public function FormAdd($info)
    {
        $result = $this->validate(
            ['name' => 'require|unique:model',
                'tablename' => 'require'],
            ['name.require' => '表单名称不能为空',
                'tablename.require' => '表名不能为空',
                'name.unique' => '表单名称已经存在']
        )->allowField(true)->save($info);
        if (false === $result) {
            $this->error = $this->getError();
            return false;
        } else {
            //更新缓存
            cache("Model", null);
            return $this->getAttr('modelid');
        }
    }

    //编辑表单
    public function FormSave($info, $modelid)
    {
        if (empty($info['name']) || empty($modelid)) {
            $this->error = "参数不得为空";
            return false;
        }
        $result = $this->where(['modelid' => $modelid, 'type' => $this->getModelType()])->update($info);
        if (false === $result) {
            $this->error = $this->getError();
            return false;
        } else {
            //更新缓存
            cache("Model", null);
            return true;
        }
    }

    //返回模型类型
    public function getModelType()
    {
        return $this->modelType;
    }

    //缓存生成
    public function formguide_cache()
    {
        $formguide_cache = $this->getModelAll($this->modelType);
        if (!empty($formguide_cache)) {
            cache('Model_form', $formguide_cache);
        }
        return $formguide_cache;
    }

}
