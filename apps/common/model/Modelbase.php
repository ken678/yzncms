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
namespace app\common\model;
use \think\Model;
use \think\Config;
use \think\Db;

/**
 * 公共模型
 */
class Modelbase extends Model
{
    /**
     * 删除表
     * @param string $tablename 不带表前缀的表名
     * @return type
     */
    public function drop_table($tablename) {
        $tablename = Config::get("database.prefix") . $tablename;
        return Db::query("DROP TABLE $tablename");
    }

    /**
     *  读取全部表名
     * @return type
     */
    public function getTables(){
        return Db::connect()->getTables();
    }

    /**
     * 检查表是否存在
     * $table 不带表前缀
     */
    public function table_exists($table) {
        $tables = $this->getTables();
        return in_array(Config::get("database.prefix") . $table, $tables) ? true : false;
    }
}