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
namespace app\admin\model\formguide;

use think\Db;
use think\facade\Cache;
use think\facade\Config;
use think\Model;

class Models extends Model
{
    protected $name               = 'model';
    protected $autoWriteTimestamp = true;

    protected static function init()
    {
        //添加
        self::beforeInsert(function ($row) {
            $setting['forward']          = $row->forward;
            $setting['mails']            = $row->mails;
            $setting['interval']         = $row->interval;
            $setting['allowmultisubmit'] = $row->allowmultisubmit;
            $setting['allowunreg']       = $row->allowunreg;
            $setting['isverify']         = $row->isverify;
            $setting['show_template']    = $row->show_template;

            $row['setting']   = serialize($setting);
            $row['module']    = 'formguide';
            $row['tablename'] = $row['tablename'] ? 'form_' . $row['tablename'] : '';
            $info             = null;
            try {
                $info = Db::name($row['tablename'])->getPk();
            } catch (\Exception $e) {
            }
            if ($info) {
                throw new Exception("数据表已经存在");
            }
        });
        self::afterInsert(function ($row) {
            cache::set("Model", null);
            cache::set('ModelField', null);

            $prefix = Config::get("database.prefix");
            //创建模型表
            $sql = "CREATE TABLE `{$prefix}{$row['tablename']}` (
                `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                `uid` mediumint(8) unsigned NOT NULL,
                `username` varchar(20) NOT NULL,
                `inputtime` int(10) unsigned NOT NULL,
                `ip` char(15) NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            Db::execute($sql);
        });
        //编辑
        self::beforeUpdate(function ($row) {
            unset($row['type'], $row['tablename']);
            $changedData = $row->getChangedData();
            $setting     = [];
            if (isset($changedData['forward'])) {
                $setting['forward'] = $row->forward;
            }
            if (isset($changedData['mails'])) {
                $setting['mails'] = $row->mails;
            }
            if (isset($changedData['interval'])) {
                $setting['interval'] = $row->interval;
            }
            if (isset($changedData['allowmultisubmit'])) {
                $setting['allowmultisubmit'] = $row->allowmultisubmit;
            }
            if (isset($changedData['allowunreg'])) {
                $setting['allowunreg'] = $row->allowunreg;
            }
            if (isset($changedData['isverify'])) {
                $setting['isverify'] = $row->isverify;
            }
            if (isset($changedData['show_template'])) {
                $setting['show_template'] = $row->show_template;
            }
            if ($setting) {
                $row['setting'] = serialize($setting);
            }
        });
        self::afterUpdate(function ($row) {
            //更新缓存
            cache::set("Model", null);
            cache::set('ModelField', null);
        });
        //删除
        self::afterDelete(function ($row) {
            cache::set("Model", null);
            cache::set('ModelField', null);
            //删除所有和这个模型相关的字段
            Db::name("ModelField")->where("modelid", $row['id'])->delete();
            //删除主表
            $table_name = Config::get("database.prefix") . $row['tablename'];
            Db::execute("DROP TABLE IF EXISTS `{$table_name}`");
        });
    }

    public function getSettingAttr($value, $data)
    {
        return unserialize($value);
    }

    public function getTableNameAttr($value, $data)
    {
        return str_replace("form_", "", $value);
    }
}
