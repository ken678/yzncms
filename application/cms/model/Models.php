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
// | 模型模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use think\Db;
use think\facade\Config;
use \think\Model;

/**
 * 模型
 */
class Models extends Model
{
    protected $name = 'model';

    /**
     * 创建模型
     * @param type $data 提交数据
     * @return boolean
     */
    public function addModel($data)
    {
        if (empty($data)) {
            return false;
        }
        //模型添加验证
        /*$validate = Loader::validate('Models');
        if (!$validate->scene('add')->check($data)) {
        $this->error = $validate->getError();
        return false;
        }*/
        //添加模型记录
        $modelid = $this->allowField(true)->save($data);
        if ($modelid) {
            //创建模型表和模型附表
            if ($this->createModel($data['tablename'], $this->modelid)) {
                cache("Model", null);
                return $this->modelid;
            } else {
                //表创建失败
                $this->where(array("modelid" => $this->modelid))->delete();
                $this->error = '数据表创建失败！';
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * 创建内容模型
     * @param type $tableName 模型主表名称（不包含表前缀）
     * @param type $modelId 模型id
     * @return boolean
     */
    protected function createModel($tableName, $modelId)
    {
        if (empty($tableName) || $modelId < 1) {
            return false;
        }
        var_dump(1111);
        exit();

        //表前缀
        /* $dbPrefix = Config::get("database.prefix");
    $ModeSql = file_get_contents($this->libPath . self::ModeSql);
    //创建一张主表和附表并插入进模型基础字段数据
    $sqlSplit = str_replace(array('@yzncms@', '@zhubiao@', '@modelid@'), array($dbPrefix, $tableName, $modelId), $ModeSql);
    return $this->sql_execute($sqlSplit, $dbPrefix);*/
    }

}
