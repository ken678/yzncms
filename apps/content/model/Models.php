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
use \think\Db;
use \think\Loader;
use \think\Config;
use \think\Model;
use \think\Cache;

/**
 * 菜单基础模型
 */
class Models extends Modelbase
{
    const ModeSql = 'Data/Sql/model.sql'; //模型SQL模板文件

    private $libPath = '';
    protected $name = 'model';
    protected $auto = ['addtime','tablename'];
    protected function setAddtimeAttr($value)
    {
        return time();
    }
    protected function setTablenameAttr($value)
    {
        return strtolower($value);//强制表名为小写
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
    public function addModel($data) {
        if (empty($data)) {
            return false;
        }
        //模型添加验证
        $validate = Loader::validate('Models');
        if(!$validate->scene('add')->check($data)){
            $this->error = $validate->getError();
            return false;
        }
        //添加模型记录
        $modelid = $this->allowField(true)->save($data);
        if ($modelid) {
            //创建模型表和模型附表
            if ($this->createModel($data['tablename'], $this->modelid)) {
                cache("Model", NULL);
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
        cache("Model", NULL);
        //删除所有和这个模型相关的字段
        Db::name("ModelField")->where(array("modelid" => $modelid))->delete();
        //删除主表
        $this->deleteTable($model_table);
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
    public function deleteTable($table) {
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
    protected function createModel($tableName, $modelId) {
        if (empty($tableName) || $modelId < 1) {
            return false;
        }
        //表前缀
        $dbPrefix = Config::get("database.prefix");
        $ModeSql = file_get_contents($this->libPath . self::ModeSql);
        $sqlSplit = str_replace(array('@yzncms@', '@zhubiao@', '@modelid@'), array($dbPrefix, $tableName, $modelId), $ModeSql);
        return $this->sql_execute($sqlSplit,$dbPrefix);
    }

    /**
     * 执行SQL
     * @param type $sqls SQL语句
     * @return boolean
     */
    protected function sql_execute($sqls, $tablepre) {
        $sqls = $this->sql_split($sqls, $tablepre);
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
     * 解析数据库语句函数
     * @param string $sql
     *          sql语句 带默认前缀的
     * @param string $tablepre
     *          自己的前缀
     * @return multitype:string 返回最终需要的sql语句
     */
    public function sql_split($sql, $tablepre) {
        if ($tablepre != "yzn_")
            $sql = str_replace ( "yzn_", $tablepre, $sql );
        $sql = preg_replace ( "/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql );

        $sql = str_replace ( "\r", "\n", $sql );
        $ret = array ();
        $num = 0;
        $queriesarray = explode ( ";\n", trim ( $sql ) );
        unset ( $sql );
        foreach ( $queriesarray as $query ) {
            $ret [$num] = '';
            $queries = explode ( "\n", trim ( $query ) );
            $queries = array_filter ( $queries );
            foreach ( $queries as $query ) {
                $str1 = substr ( $query, 0, 1 );
                if ($str1 != '#' && $str1 != '-')
                    $ret [$num] .= $query;
            }
            $num ++;
        }
        return $ret;
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
        Cache::set('Model', $data);
        return $data;
    }


}