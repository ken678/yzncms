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
    );

    //卸载
    public function run()
    {
        // 内容模型的表名列表
        $table_list = Db::name('model')->column('tablename');
        if ($table_list) {
            foreach ($table_list as $tablename) {
                // 删除内容模型表
                if (!empty($tablename)) {
                    $tablename = config('database.prefix') . $tablename;
                    Db::execute("DROP TABLE IF EXISTS `{$tablename}`;");
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
