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

use app\admin\model\cms\Cms as CmsModel;
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

    //
    public function getFieldInfo($modelId, $id = null)
    {

        $list = self::where('modelid', $modelId)->where('status', 1)->order('listorder asc,id asc')->column("name,title,remark,type,isadd,iscore,ifsystem,ifrequire,setting");
        if (!empty($list)) {
            if ($id) {
                $modelInfo = Db::name('Model')->where('id', $modelId)->field('tablename,type')->find();
                $dataInfo  = Db::name($modelInfo['tablename'])->where('id', $id)->find();
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
    public function deleteInfo($modeId, $id)
    {
        $modelInfo = cache('Model');
        $modelInfo = $modelInfo[$modeId];
        $data      = Db::name($modelInfo['tablename'])->where('id', $id)->find();
        if (empty($data)) {
            throw new \Exception("该信息不存在！");
        }
        return Db::name($modelInfo['tablename'])->where('id', $id)->delete();
    }
}
