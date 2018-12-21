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

    private $libPath = '';
    protected $name = 'model';
    protected $ext_table = '_data';

    // 模型初始化
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
        //添加模型记录
        $modelid = self::create($data);
        if ($modelid) {
            //创建模型表和模型附表
            if ($this->createTable($data)) {
                cache("Model", null);
                return true;
            } else {
                //表创建失败
                self::destroy($modelid->getAttr('id'));
                $this->error = '数据表创建失败！';
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * 创建内容模型
     */
    protected function createTable($data)
    {
        $data['tablename'] = strtolower($data['tablename']);
        $table = Config::get("database.prefix") . $data['tablename'];
        if ($this->tableExist($table)) {
            throw new \Exception('创建失败！' . $table . '表已经存在~');
        }
        $sql = <<<EOF
				CREATE TABLE `{$table}` (
				`id` mediumint(8) unsigned NOT NULL auto_increment,
				`catid` smallint(5) unsigned NOT NULL DEFAULT '0',
				`typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
				`title` varchar(255) NOT NULL DEFAULT '',
				`style` varchar(24) NOT NULL DEFAULT '',
				`thumb` varchar(255) NOT NULL DEFAULT '',
				`keywords` varchar(255) NOT NULL DEFAULT '',
				`tags` varchar(255) NOT NULL DEFAULT '',
				`description` varchar(255) NOT NULL DEFAULT '',
				`posid` tinyint(1) unsigned NOT NULL DEFAULT '0',
				`url` varchar(255) NOT NULL DEFAULT '',
				`listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
				`status` tinyint(2) unsigned NOT NULL DEFAULT '1',
				`sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
				`islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
				`username` char(20) NOT NULL DEFAULT '',
				`inputtime` int(10) unsigned NOT NULL DEFAULT '0',
				`updatetime` int(10) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY  (`id`),
				KEY `status` (`status`,`listorder`,`id`),
				KEY `listorder` (`catid`,`status`,`listorder`,`id`),
				KEY `catid` (`catid`,`status`,`id`),
				KEY `thumb` (`thumb`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOF;
        Db::execute($sql);
        if ($data['type'] == 2) {
            // 新建附属表
            $sql = <<<EOF
				CREATE TABLE `{$table}{$this->ext_table}` (
				`id` mediumint(8) unsigned DEFAULT '0',
				`content` text,
				`paginationtype` tinyint(1) NOT NULL DEFAULT '0',
				`maxcharperpage` mediumint(6) NOT NULL DEFAULT '0',
				`template` varchar(30) NOT NULL DEFAULT '',
				`paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
				`allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
				`relation` varchar(255) NOT NULL DEFAULT '',
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOF;
        }
        Db::execute($sql);
        return true;

        //表前缀
        /* $dbPrefix = Config::get("database.prefix");
    $ModeSql = file_get_contents($this->libPath . self::ModeSql);
    //创建一张主表和附表并插入进模型基础字段数据
    $sqlSplit = str_replace(array('@yzncms@', '@zhubiao@', '@modelid@'), array($dbPrefix, $tableName, $modelId), $ModeSql);
    return $this->sql_execute($sqlSplit, $dbPrefix);*/
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

    protected function tableExist($table)
    {
        if (true == Db::query("SHOW TABLES LIKE '{$table}'")) {
            return true;
        } else {
            return false;
        }
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
