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

use think\Db;
use think\facade\Validate;
use think\Model;

class Formguide extends Model
{
    protected $name               = 'ModelField';
    protected $autoWriteTimestamp = false;

    //添加模型内容
    public function addFormguideData($formid, $data)
    {
        //完整表名获取
        $tablename        = $this->getModelTableName($formid);
        $data['uid']      = \app\member\service\User::instance()->id ?: 0;
        $data['username'] = \app\member\service\User::instance()->username ?: '游客';
        //处理数据
        $data              = $this->dealModelPostData($formid, $data);
        $data['inputtime'] = request()->time();
        $data['ip']        = request()->ip();
        try {
            //主表
            $id = Db::name($tablename)->insertGetId($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $id;
    }

    //查询解析模型数据用以构造from表单
    public function getFieldList($modelid, $id = null)
    {
        $tablename = $this->getModelTableName($modelid);

        $list = self::where('modelid', $modelid)->where('status', 1)->order('listorder DESC,id DESC')->column("name,title,remark,type,isadd,iscore,ifsystem,ifrequire,setting");
        if (!empty($list)) {
            //编辑信息时查询出已有信息
            if ($id) {
                $dataInfo = Db::name($tablename)->where('id', $id)->find();
            }
            foreach ($list as $key => &$value) {
                //内部字段不显示
                if ($value['iscore']) {
                    unset($list[$key]);
                }
                //核心字段做标记
                $value['fieldArr'] = 'modelField';
                if (isset($dataInfo[$value['name']])) {
                    $value['value'] = $dataInfo[$value['name']];
                }
                //扩展配置
                $value['setting'] = unserialize($value['setting']);
                $value['options'] = $value['setting']['options'] ?? '';
                //在新增时候添加默认值
                if (!$id) {
                    $value['value'] = $value['setting']['value'] ?? '';
                }
                if ($value['options'] != '') {
                    $value['options'] = parse_attr($value['options']);
                }
                if ($value['type'] == 'checkbox') {
                    $value['value'] = empty($value['value']) ? [] : explode(',', $value['value']);
                }
                if ($value['type'] == 'datetime') {
                    $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $value['value']);
                }
                if ($value['type'] == 'date') {
                    $value['value'] = empty($value['value']) ? '' : date('Y-m-d', $value['value']);
                }
                if ($value['type'] == 'Ueditor' || $value['type'] == 'markdown') {
                    $value['value'] = isset($value['value']) ? htmlspecialchars_decode($value['value']) : '';
                }
            }
        }
        return $list;
    }

    //处理post提交的模型数据
    protected function dealModelPostData($modelid, $data)
    {
        //字段类型
        $query = self::where('modelid', $modelid)->where('status', 1);

        $filedTypeList = $query->order('listorder DESC, id DESC')->column('name,title,type,ifsystem,ifrequire,pattern,errortips');

        foreach ($filedTypeList as $name => $vo) {
            if (!isset($data[$name])) {
                switch ($vo['type']) {
                    // 开关
                    case 'switch':
                        $data[$name] = 0;
                        break;
                    case 'checkbox':
                        $data[$name] = '';
                        break;
                }
            } else {
                if (is_array($data[$name])) {
                    $data[$name] = implode(',', $data[$name]);
                }
                switch ($vo['type']) {
                    // 开关
                    case 'switch':
                        $data[$name] = 1;
                        break;
                    // 日期+时间
                    case 'datetime':
                        //if ($vo['ifeditable']) {
                        $data[$name] = strtotime($data[$name]);
                        //}
                        break;
                    // 日期
                    case 'date':
                        $data[$name] = strtotime($data[$name]);
                        break;
                    // 编辑器
                    case 'markdown':
                    case 'Ueditor':
                        $data[$name] = htmlspecialchars(stripslashes($data[$name]));
                        break;
                }
            }
            //数据必填验证
            if ($vo['ifrequire'] && (!isset($data[$name]) || $data[$name] == '')) {
                throw new \Exception("'" . $vo['title'] . "'必须填写~");
            }
            //正则校验
            if (isset($data[$name]) && $data[$name] && $vo['pattern'] && !Validate::regex($data[$name], $vo['pattern'])) {
                throw new \Exception("'" . $vo['title'] . "'" . (!empty($vo['errortips']) ? $vo['errortips'] : '正则校验失败') . "");
            }
            //数据格式验证
            if (!empty($data[$name]) && in_array($vo['type'], ['number']) && !Validate::isNumber($data[$name])) {
                throw new \Exception("'" . $vo['title'] . "'格式错误~");
                //安全过滤
            } else {

            }
        }
        return $data;
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
