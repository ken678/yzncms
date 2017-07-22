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
use \think\Model;
use \think\Cache;
use \think\Db;
use \think\Config;
/**
 * 菜单基础模型
 */
class Models extends Model
{
    private $libPath = ''; //当前模块路径
    protected $name = 'model';
    const mainTableSql = 'Data/Sql/lvyecms_zhubiao.sql'; //模型主表SQL模板文件
    const sideTablesSql = 'Data/Sql/lvyecms_zhubiao_data.sql'; //模型副表SQL模板文件
    const modelTablesInsert = 'Data/Sql/lvyecms_insert.sql'; //可用默认模型字段

    //初始化
    protected function initialize() {
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
        //强制表名为小写
        $data['tablename'] = strtolower($data['tablename']);
        //添加模型记录
        $modelid = $this->save($data);
        if ($modelid) {
            //创建数据表
            if ($this->createModel($data['tablename'], $modelid)) {
                cache("Model", NULL);
                return $modelid;
            } else {
                //表创建失败
                $this->where(array("modelid" => $modelid))->delete();
                $this->error = '数据表创建失败！';
                return false;
            }
        } else {
            return false;
        }

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
        //读取模型主表SQL模板
        $mainTableSqll = file_get_contents($this->libPath . self::mainTableSql);
        //副表
        $sideTablesSql = file_get_contents($this->libPath . self::sideTablesSql);
        //字段数据
        $modelTablesInsert = file_get_contents($this->libPath . self::modelTablesInsert);
        //表前缀，表名，模型id替换
        $sqlSplit = str_replace(array('@lvyecms@', '@zhubiao@', '@modelid@'), array($dbPrefix, $tableName, $modelId), $mainTableSqll . "\n" . $sideTablesSql . "\n" . $modelTablesInsert);
        return $this->sql_execute($sqlSplit);
    }

    /**
     * 执行SQL
     * @param type $sqls SQL语句
     * @return boolean
     */
    protected function sql_execute($sqls) {
        $sqls = $this->sql_split($sqls);
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
    public function sql_split($sql, $tablepre="yzn_") {
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