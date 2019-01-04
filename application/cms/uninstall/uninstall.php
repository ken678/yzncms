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
// | 卸载脚本
// +----------------------------------------------------------------------
namespace app\cms\uninstall;

use think\Db;
use \sys\UninstallBase;

class Uninstall extends UninstallBase
{
    //固定相关表
    private $modelTabList = array(
        'category',
        'model',
        'model_field',
        'article',
        'article_data',
        'page',
    );
    protected $ext_table = '_data';

    //卸载
    public function run()
    {
        // 内容模型的表名列表
        $table_list = Db::name('model')->field('tablename,type')->select();
        if ($table_list) {
            foreach ($table_list as $val) {
                $tablename = config('database.prefix') . $val['tablename'];
                Db::execute("DROP TABLE IF EXISTS `{$tablename}`;");
                if ($val['type'] == 2) {
                    Db::execute("DROP TABLE IF EXISTS `{$tablename}{$this->ext_table}`;");
                }
            }
        }
        //删除对应模型数据表
        if (!empty($this->modelTabList)) {
            foreach ($this->modelTabList as $tablename) {
                //删除固定表
                if (!empty($tablename)) {
                    $tablename = config('database.prefix') . $tablename;
                    Db::execute("DROP TABLE IF EXISTS `{$tablename}`;");
                }
            }
        }
        return true;
    }

}
