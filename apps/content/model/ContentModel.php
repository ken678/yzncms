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
use \think\Db;

class ContentModel extends Model
{
	//当前模型id
    public $modelid = 0;

    /**
     * 取得内容模型实例
     * @param type $modelid 模型ID
     * @return obj
     */
    static public function getInstance($modelid) {
        //静态成品变量 保存全局实例
        static $_instance = NULL;
        if (is_null($_instance[$modelid]) || !isset($_instance[$modelid])) {
            //内容模型缓存
            $modelCache = cache("Model");
            if (empty($modelCache[$modelid])) {
                return false;
            }
            $tableName = $modelCache[$modelid]['tablename'];
            $_instance[$modelid] = Db::name(ucwords($tableName));
            //内容模型
            if ($modelCache[$modelid]['type'] == 0) {
               /* $_instance[$modelid]->_validate = array(
                    //栏目
                    array('catid', 'require', '请选择栏目！', 1, 'regex', 1),
                    array('catid', 'isUltimate', '该模型非终极栏目，无法添加信息！', 1, 'callback', 1),
                    //标题
                    array('title', 'require', '标题必须填写！', 1, 'regex', 1),
                );*/
            }
            //设置模型id
            $_instance[$modelid]->modelid = $modelid;
        }
        return $_instance[$modelid];
    }

}