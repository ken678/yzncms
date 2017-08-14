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
namespace app\content\logic;
use think\Db;
use think\Model;

class Content extends Model
{

	public function delete($id = '', $catid = '')
	{
		$catInfo = getCategory($catid);
        if (empty($catInfo)) {
            $this->error='该栏目不存在！';
            return false;
        }
        $modelid = $catInfo['modelid'];//模型ID
        $modelCache = cache("Model");//模型缓存
        if (empty($modelCache[$modelid])) {
            return false;
        }
        $tableName = $modelCache[$modelid]['tablename'];

        //删除主表和从表_data
        $r =Db::name($tableName)->delete($id);
        if($r){
        	$tableName = $tableName.'_data';
            Db::name($tableName)->delete($id);
            return true;
        }
        return fasle;
	}
}