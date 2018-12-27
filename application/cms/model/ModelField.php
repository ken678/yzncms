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
// | 字段模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use app\common\model\Modelbase;
use think\Db;

/**
 * 字段模型
 */
class ModelField extends Modelbase
{
    //添加字段
    public function addField($data = null)
    {
        $data['ifsystem'] = isset($data['ifsystem']) ? intval($data['ifsystem']) : 0;
        //模型id
        $modelid = $data['modelid'];
        //完整表名获取 判断主表 还是副表
        $tablename = $this->getModelTableName($modelid, $data['ifsystem']);
        if (!$this->table_exists($tablename)) {
            $this->error = '数据表不存在！';
            return false;
        }
        $tablename = config('database.prefix') . $tablename;
        //判断字段名唯一性
        if ($this->where('name', $data['name'])->where('modelid', $modelid)->value('id')) {
            $this->error = "字段'" . $data['name'] . "`已经存在";
            return false;
        }

        $data['ifeditable'] = isset($data['ifeditable']) ? intval($data['ifeditable']) : 0;
        $data['ifrequire'] = isset($data['ifrequire']) ? intval($data['ifrequire']) : 0;
        if ($data['ifrequire'] && !$data['ifeditable']) {
            $this->error = '必填字段不可以隐藏！';
            return false;
        }
        //先将字段存在设置的主表或附表里面 再将数据存入ModelField
        $sql = <<<EOF
            ALTER TABLE `{$tablename}`
            ADD COLUMN `{$data['name']}` {$data['define']} COMMENT '{$data['title']}';
EOF;
        try {
            $res = Db::execute($sql);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        $fieldInfo = Db::name('field_type')->where('name', $data['formtype'])->field('ifoption,ifstring')->find();
        //只有主表文本类字段才可支持搜索
        $data['ifsearch'] = isset($data['ifsearch']) ? ($fieldInfo['ifstring'] && $data['ifsystem'] ? intval($data['ifsearch']) : 0) : 0;
        $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
        $data['iffixed'] = 0;
        $data['setting'] = $fieldInfo['ifoption'] ? $data['setting'] : '';
        $fieldid = self::create($data, true);
        if ($fieldid) {
            //清理缓存
            //cache('ModelField', null);
            return true;
        } else {
            $this->error = '字段信息入库失败！';
            //回滚
            Db::execute("ALTER TABLE  `{$tablename}` DROP  `{$data['name']}`");
            return false;
        }
        return true;
    }

    /**
     * 根据模型ID，返回表名
     * @param type $modelid
     * @param type $modelid
     * @return string
     */
    protected function getModelTableName($modelid, $ifsystem = 1)
    {
        //读取模型配置 以后优化缓存形式
        $model_cache = cache("Model");
        //表名获取
        $model_table = $model_cache[$modelid]['tablename'];
        //完整表名获取 判断主表 还是副表
        $tablename = $ifsystem ? $model_table : $model_table . "_data";
        return $tablename;
    }

}
