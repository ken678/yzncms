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
use \think\Cache;
use \think\Db;
/**
 * 菜单基础模型
 */
class Models extends Model
{
    /**
     * 创建模型
     * @param type $data 提交数据
     * @return boolean
     */
    public function addModel($data) {
        if (empty($data)) {
            return false;
        }
        var_dump($data);
        exit();
        //强制表名为小写
        $data['tablename'] = strtolower($data['tablename']);
        //添加模型记录
        $modelid = $this->add($data);
        if ($modelid) {
            //创建数据表
            if ($this->createModel($data['tablename'], $modelid)) {
                cache("Model", NULL);
                return $modelid;
            } else {
                //表创建失败
                $this->where(array("modelid" => $modelid))->delete();
                $this->error = '数据表创建失败！';
                return false;
            }
        } else {
            return false;
        }

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

    /**
     * 生成模型缓存，以模型ID为下标的数组
     * @return boolean
     */
    public function model_cache()
    {
        $data = $this->getModelAll();
        Cache::set('Model', $data);
        return $data;
    }


}