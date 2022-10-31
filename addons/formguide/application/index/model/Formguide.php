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
// | 表单模型
// +----------------------------------------------------------------------
namespace app\index\model;

use app\cms\model\Cms as CmsModel;
use think\Db;

class Formguide extends CmsModel
{
    protected $name               = 'ModelField';
    protected $autoWriteTimestamp = false;

    //添加模型内容
    public function addFormguideData($formid, $data, $dataExt = [])
    {
        //完整表名获取
        $tablename = $this->getModelTableName($formid);
        if (!$this->table_exists($tablename)) {
            throw new \Exception('数据表不存在！');
        }
        $data['uid']      = \app\member\service\User::instance()->id ?: 0;
        $data['username'] = \app\member\service\User::instance()->username ?: '游客';
        //处理数据
        $dataAll              = $this->dealModelPostData($formid, $data, $dataExt);
        list($data, $dataExt) = $dataAll;
        $data['inputtime']    = request()->time();
        $data['ip']           = request()->ip();
        try {
            //主表
            $id = Db::name($tablename)->insertGetId($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $id;
    }
}
