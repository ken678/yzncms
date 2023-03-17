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
        $tablename        = 'form_' . $this->getModelTableName($formid);
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

    /**
     * 根据模型ID，返回表名
     * @param type $modelid
     * @return string
     */
    protected function getModelTableName($modelid, $ifsystem = 1)
    {
        //读取模型配置 以后优化缓存形式
        $model_cache = cache("Model");
        //表名获取
        return $model_cache[$modelid]['tablename'];
    }
}
