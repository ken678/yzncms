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

use app\common\model\Modelbase;
use think\Cache;
use think\Config;
use think\Db;
use think\Loader;
use think\Model;
use util\Sql;

/**
 * 菜单基础模型
 */
class Models extends Modelbase
{
    const ModeSql = 'Data/Sql/model.sql'; //模型SQL模板文件
    const membershipModelSql = 'Data/Sql/member.sql'; //会员模型

    private $libPath = '';
    protected $name = 'model';
    protected $auto = ['addtime', 'tablename'];
    protected function setAddtimeAttr($value)
    {
        return time();
    }
    protected function setTablenameAttr($value)
    {
        return strtolower($value); //强制表名为小写
    }

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->libPath = APP_PATH . 'content/';
    }

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
        $validate = Loader::validate('Models');
        if (!$validate->scene('add')->check($data)) {
            $this->error = $validate->getError();
            return false;
        }
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
     * 编辑模型
     * @param type $data 提交数据
     * @return boolean
     */
    public function editModel($data, $modelid = 0)
    {
        if (empty($data)) {
            return false;
        }
        //模型ID
        $modelid = $modelid ? $modelid : (int) $data['modelid'];
        if (!$modelid) {
            $this->error = '模型ID不能为空！';
            return false;
        }
        //查询模型数据
        $info = $this->where(array("modelid" => $modelid))->find();
        if (empty($info)) {
            $this->error = '该模型不存在！';
            return false;
        }
        $data['modelid'] = $modelid;
        //模型添加验证
        $validate = Loader::validate('Models');
        if (!$validate->scene('edit')->check($data)) {
            $this->error = $validate->getError();
            return false;
        }
        //是否更改表名
        if ($info['tablename'] != $data['tablename'] && !empty($data['tablename'])) {
            //检查新表名是否存在
            if ($this->table_exists($data['tablename']) || $this->table_exists($data['tablename'] . '_data')) {
                $this->error = '该表名已经存在！';
                return false;
            }
            if (false !== $this->allowField(true)->save($data, array("modelid" => $modelid))) {
                //表前缀
                $dbPrefix = Config::get("database.prefix");
                //表名更改
                if (!$this->sql_execute("RENAME TABLE  `{$dbPrefix}{$info['tablename']}` TO  `{$dbPrefix}{$data['tablename']}` ;")) {
                    $this->error = '数据库修改表名失败！';
                    return false;
                }
                //修改副表
                if (!$this->sql_execute("RENAME TABLE  `{$dbPrefix}{$info['tablename']}_data` TO  `{$dbPrefix}{$data['tablename']}_data` ;")) {
                    //主表已经修改，进行回滚
                    $this->sql_execute("RENAME TABLE  `{$dbPrefix}{$data['tablename']}` TO  `{$dbPrefix}{$info['tablename']}` ;");
                    $this->error = '数据库修改副表表名失败！';
                    return false;
                }
                //更新缓存
                cache("Model", null);
                return true;
            } else {
                $this->error = '模型更新失败！';
                return false;
            }
        } else {
            if (false !== $this->allowField(true)->save($data, array("modelid" => $modelid))) {
                //更新缓存
                cache("Model", null);
                return true;
            } else {
                $this->error = '模型更新失败！';
                return false;
            }
        }

    }

    /**
     * 根据模型ID删除模型
     * @param type $modelid 模型id
     * @return boolean
     */
    public function deleteModel($modelid)
    {
        if (empty($modelid)) {
            return false;
        }
        $modeldata = $this->where(array("modelid" => $modelid))->find();
        if (!$modeldata) {
            return false;
        }
        //表名
        $model_table = $modeldata['tablename'];
        //删除模型数据
        $this->where(array("modelid" => $modelid))->delete();
        //更新缓存
        cache("Model", null);
        //删除所有和这个模型相关的字段
        Db::name("ModelField")->where(array("modelid" => $modelid))->delete();
        //删除主表
        $this->deleteTable($model_table);
        //type不为0则无附表 不需要删除
        if ((int) $modeldata['type'] == 0) {
            //删除副表
            $this->deleteTable($model_table . "_data");
        }
        return true;
    }

    /**
     * 删除表
     * $table 不带表前缀
     */
    public function deleteTable($table)
    {
        if ($this->table_exists($table)) {
            $this->drop_table($table);
        }
        return true;
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
        //表前缀
        $dbPrefix = Config::get("database.prefix");
        $ModeSql = file_get_contents($this->libPath . self::ModeSql);
        //创建一张主表和附表并插入进模型基础字段数据
        $sqlSplit = str_replace(array('@yzncms@', '@zhubiao@', '@modelid@'), array($dbPrefix, $tableName, $modelId), $ModeSql);
        return $this->sql_execute($sqlSplit, $dbPrefix);
    }

    /**
     * 创建会员模型
     * @param type $tableName 模型主表名称（不包含表前缀）
     * @param type $modelId 所属模型id
     * @return boolean
     */
    public function AddModelMember($tableName, $modelId)
    {
        if (empty($tableName)) {
            return false;
        }
        //表前缀
        $dbPrefix = Config::get("database.prefix");
        //读取会员模型SQL模板
        $membershipModelSql = file_get_contents($this->libPath . self::membershipModelSql);
        //表前缀，表名，模型id替换
        $sqlSplit = str_replace(array('@yzncms@', '@zhubiao@', '@modelid@'), array($dbPrefix, $tableName, $modelId), $membershipModelSql);
        return $this->sql_execute($sqlSplit, $dbPrefix);
    }

    /**
     * 执行SQL
     * @param type $sqls SQL语句
     * @return boolean
     */
    protected function sql_execute($sqls, $tablepre)
    {
        $sqls = Sql::parseSql($sqls);
        if (is_array($sqls)) {
            foreach ($sqls as $sql) {
                if (trim($sql) != '') {
                    Db::execute($sql);
                }
            }
        } else {
            Db::execute($sqls);
        }
        return true;
    }

    /**
     * 根据模型类型取得数据用于缓存
     * @param type $type
     * @return type
     */
    public function getModelAll($type = null)
    {
        $where = array('disabled' => 0);
        if (!is_null($type)) {
            $where['type'] = $type;
        }
        $data = Db::name('model')->where($where)->select();
        $Cache = array();
        foreach ($data as $v) {
            $Cache[$v['modelid']] = $v;
        }
        return $Cache;
    }

    /**
     * 生成模型缓存，以模型ID为下标的数组
     * @return boolean
     */
    public function model_cache()
    {
        $data = $this->getModelAll();
        Cache('Model', $data);
        return $data;
    }

}
