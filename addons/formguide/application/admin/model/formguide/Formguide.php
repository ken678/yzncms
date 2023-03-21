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
namespace app\admin\model\formguide;

use think\Db;
use think\Model;

class Formguide extends Model
{
    protected $name               = 'ModelField';
    protected $autoWriteTimestamp = false;

    //
    public function getFieldInfo($modelid, $id = null)
    {
        //完整表名获取
        $tablename = $this->getModelTableName($modelid);
        $list      = self::where('modelid', $modelid)->where('status', 1)->order('listorder asc,id asc')->column("name,title,remark,type,isadd,iscore,ifsystem,ifrequire,setting");
        if (!empty($list)) {
            if ($id) {
                $dataInfo = Db::name($tablename)->where('id', $id)->find();
            }
            foreach ($list as $key => &$value) {
                if ($value['iscore']) {
                    unset($list[$key]);
                }

                if (isset($dataInfo[$value['name']])) {
                    $value['value'] = $dataInfo[$value['name']];
                }

                $value['setting'] = unserialize($value['setting']);
                $value['options'] = $value['setting']['options'];
                if ('' != $value['options']) {
                    $value['options'] = parse_attr($value['options']);
                }
                if ($value['type'] == 'image' || $value['type'] == 'file') {
                    $value['value'] = !empty($value['value']) ? '<a href="' . $value['value'] . '" target="_blank">[查看]</a>' : '';
                }
                if ($value['type'] == 'datetime') {
                    $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $value['value']);
                }
            }
        }
        return $list;
    }

    //删除信息
    public function deleteInfo($modelid, $id)
    {
        //完整表名获取
        $tablename = $this->getModelTableName($modelid);
        $data      = Db::name($tablename)->where('id', $id)->find();
        if (empty($data)) {
            throw new \Exception("该信息不存在！");
        }
        return Db::name($tablename)->where('id', $id)->delete();
    }

    /**
     * 根据模型ID，返回表名
     * @param type $modelid
     * @return string
     */
    protected function getModelTableName($modelid)
    {
        //读取模型配置 以后优化缓存形式
        $model_cache = cache("Model");
        //表名获取
        return 'form_' . $model_cache[$modelid]['tablename'];
    }
}
