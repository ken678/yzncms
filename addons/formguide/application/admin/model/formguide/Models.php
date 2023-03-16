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
use think\facade\Config;
use think\Model;

class Models extends Model
{
    protected $name               = 'model';
    protected $autoWriteTimestamp = true;

    /**
     * 创建模型
     * @param type $data 提交数据
     * @return boolean
     */
    public function addModelFormguide($data, $module = 'formguide')
    {
        if (empty($data)) {
            throw new \Exception('数据不得为空！');
        }
        $data['tablename'] = $data['tablename'] ? 'form_' . $data['tablename'] : '';
        $data['module']    = $module;
        $data['setting']   = serialize($data['setting']);
        //添加模型记录
        if (self::allowField(true)->save($data)) {
            cache("Model", null);
            //创建模型表
            $sql = "CREATE TABLE IF NOT EXISTS `think_form_table` (
                       `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                       `uid` mediumint(8) unsigned NOT NULL,
                       `username` varchar(20) NOT NULL,
                       `inputtime` int(10) unsigned NOT NULL,
                       `ip` char(15) NOT NULL DEFAULT '',
                       PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            //表名替换
            $sql = str_replace("think_form_table", Config::get("database.prefix") . $data['tablename'], $sql);
            Db::execute($sql);
        }
    }

    /**
     * 根据模型ID删除模型
     * @param type $id 模型id
     * @return boolean
     */
    public function deleteModel($id)
    {
        $modeldata = self::where(array("id" => $id))->find();
        if (!$modeldata) {
            throw new \Exception('数据不存在！');
        }
        //删除模型数据
        self::destroy($id);
        //更新缓存
        cache("Model", null);
        //删除所有和这个模型相关的字段
        Db::name("ModelField")->where("modelid", $id)->delete();
        //删除主表
        $table_name = Config::get("database.prefix") . $modeldata['tablename'];
        Db::execute("DROP TABLE IF EXISTS `{$table_name}`");
        if ((int) $modeldata['type'] == 2) {
            //删除副表
            $table_name .= $this->ext_table;
            Db::execute("DROP TABLE IF EXISTS `{$table_name}`");
        }
        return true;
    }
}
