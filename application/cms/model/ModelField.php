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
    protected $autoWriteTimestamp = true;
    protected $insert = ['status' => 1];
    protected $ext_table = '_data';

    //添加字段
    public function addField($data = null)
    {
        $data['name'] = strtolower($data['name']);
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
        $fieldInfo = Db::name('field_type')->where('name', $data['type'])->field('ifoption,ifstring')->find();
        //只有主表文本类字段才可支持搜索
        $data['ifsearch'] = isset($data['ifsearch']) ? ($fieldInfo['ifstring'] && $data['ifsystem'] ? intval($data['ifsearch']) : 0) : 0;
        $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
        $data['iffixed'] = 0;
        $data['options'] = $fieldInfo['ifoption'] ? $data['options'] : '';
        $fieldid = self::create($data, true);
        if ($fieldid) {
            //清理缓存
            cache('ModelField', null);
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
     *  编辑字段
     * @param type $data 编辑字段数据
     * @param type $fieldid 字段id
     * @return boolean
     */
    public function editField($data, $fieldid = 0)
    {
        $data['name'] = strtolower($data['name']);
        $data['ifsystem'] = isset($data['ifsystem']) ? intval($data['ifsystem']) : 0;
        if (!$fieldid && !isset($data['fieldid'])) {
            $this->error = '缺少字段id！';
            return false;
        } else {
            $fieldid = $fieldid ? $fieldid : (int) $data['fieldid'];
        }
        //原字段信息
        $info = self::where(array("id" => $fieldid))->find();
        if (empty($info)) {
            $this->error = '该字段不存在！';
            return false;
        }
        //模型id
        $data['modelid'] = $modelid = $info['modelid'];
        //完整表名获取 判断主表 还是副表
        $tablename = $this->getModelTableName($modelid, $data['ifsystem']);
        if (!$this->table_exists($tablename)) {
            $this->error = '数据表不存在！';
            return false;
        }
        $tablename = config('database.prefix') . $tablename;
        //判断字段名唯一性
        if ($this->where('name', $data['name'])->where('modelid', $modelid)->where('id', '<>', $fieldid)->value('id')) {
            $this->error = "字段'" . $data['name'] . "`已经存在";
            return false;
        }
        $data['ifeditable'] = isset($data['ifeditable']) ? intval($data['ifeditable']) : 0;
        $data['ifrequire'] = isset($data['ifrequire']) ? intval($data['ifrequire']) : 0;
        if ($data['ifrequire'] && !$data['ifeditable']) {
            $this->error = '必填字段不可以隐藏！';
            return false;
        }
        $sql = <<<EOF
            ALTER TABLE `{$tablename}`
            CHANGE COLUMN `{$info['name']}` `{$data['name']}` {$data['define']} COMMENT '{$data['title']}';
EOF;
        try {
            Db::execute($sql);
        } catch (\Exception $e) {
            $this->addField($data);
        }
        $fieldInfo = Db::name('field_type')->where('name', $data['type'])->field('ifoption,ifstring')->find();
        //只有主表文本类字段才可支持搜索
        $data['ifsearch'] = isset($data['ifsearch']) ? ($fieldInfo['ifstring'] && $data['ifsystem'] ? intval($data['ifsearch']) : 0) : 0;
        $data['status'] = isset($data['status']) ? intval($data['status']) : 0;
        $data['options'] = $fieldInfo['ifoption'] ? $data['options'] : '';
        //清理缓存
        cache('ModelField', null);
        self::update($data, ['id' => $fieldid], true);
        return true;
    }

    /**
     * 删除字段
     * @param type $fieldid 字段id
     * @return boolean
     */
    public function deleteField($fieldid)
    {

        //原字段信息
        $info = self::where(array("id" => $fieldid))->find();
        if (empty($info)) {
            $this->error = '该字段不存在！';
            return false;
        }
        //模型id
        $modelid = $info['modelid'];
        //完整表名获取 判断主表 还是副表
        $tablename = $this->getModelTableName($modelid, $info['ifsystem']);
        if (!$this->table_exists($tablename)) {
            $this->error = '数据表不存在！';
            return false;
        }
        $tablename = config('database.prefix') . $tablename;

        //判断是否允许删除
        $sql = <<<EOF
            ALTER TABLE `{$tablename}`
            DROP COLUMN `{$info['name']}`;
EOF;
        try {
            $res = Db::execute($sql);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        self::get($fieldid)->delete();
        return true;
    }

    //添加模型内容
    public function addModelData($data, $dataExt = [])
    {
        $catid = (int) $data['catid'];
        $modelid = getCategory($catid, 'modelid');
        //完整表名获取
        $tablename = $this->getModelTableName($modelid);
        if (!$this->table_exists($tablename)) {
            throw new \Exception('数据表不存在！');
        }
        //自动提取摘要，如果有设置自动提取，且description为空，且有内容字段才执行
        $this->description($data, $dataExt);
        //处理数据
        $dataAll = $this->dealModelPostData($modelid, $data, $dataExt);
        $posid = $data['posid'];
        list($data, $dataExt) = $dataAll;
        if (!isset($data['inputtime'])) {
            $data['inputtime'] = request()->time();
        }
        if (!isset($data['updatetime'])) {
            $data['updatetime'] = request()->time();

        }
        try {
            //主表
            $id = Db::name($tablename)->insertGetId($data);
            //附表
            if (!empty($dataExt)) {
                $dataExt['did'] = $id;
                Db::name($tablename . $this->ext_table)->insert($dataExt);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        //推荐位
        if ($posid && is_array($posid)) {
            $posids = array();
            foreach ($posid as $r) {
                if ($r != '-1') {
                    $posids[] = $r;
                }
            }
            model('Position')->positionUpdate($id, $modelid, $catid, $posids, 0, 1);
        }
    }

    //编辑模型内容
    public function editModelData($data, $dataExt = [])
    {
        $catid = (int) $data['catid'];
        $id = (int) $data['id'];
        unset($data['catid']);
        unset($data['id']);
        $modelid = getCategory($catid, 'modelid');
        //完整表名获取
        $tablename = $this->getModelTableName($modelid);
        if (!$this->table_exists($tablename)) {
            throw new \Exception('数据表不存在！');
        }
        //自动提取摘要，如果有设置自动提取，且description为空，且有内容字段才执行
        $this->description($data, $dataExt);

        $dataAll = $this->dealModelPostData($modelid, $data, $dataExt);
        $posid = $data['posid'];
        list($data, $dataExt) = $dataAll;

        if (!isset($data['updatetime'])) {
            $data['updatetime'] = request()->time();
        }
        try {
            //主表
            Db::name($tablename)->where('id', $id)->update($data);
            //附表
            if (!empty($dataExt)) {
                //查询是否存在ID 不存在则新增
                if (Db::name($tablename . $this->ext_table)->where('did', $id)->find()) {
                    Db::name($tablename . $this->ext_table)->where('did', $id)->update($dataExt);
                } else {
                    $dataExt['did'] = $id;
                    Db::name($tablename . $this->ext_table)->insert($dataExt);
                };
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        //推荐位
        if ($posid && is_array($posid)) {
            $posids = array();
            foreach ($posid as $r) {
                if ($r != '-1') {
                    $posids[] = $r;
                }
            }
            model('Position')->positionUpdate($id, $modelid, $catid, $posids, 0, 0);
        }
    }

    //删除模型内容
    public function deleteModelData($modeId, $ids)
    {
        $modelInfo = cache('Model');
        if (false == $modelInfo) {
            return false;
        }
        $modelInfo = $modelInfo[$modeId];
        if (is_array($ids)) {
            try {
                Db::name($modelInfo['tablename'])->where('id', 'in', $ids)->delete();
                if (2 == $modelInfo['type']) {
                    Db::name($modelInfo['tablename'] . $this->ext_table)->where('did', 'in', $ids)->delete();
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            try {
                Db::name($modelInfo['tablename'])->where('id', $ids)->delete();
                if (2 == $modelInfo['type']) {
                    Db::name($modelInfo['tablename'] . $this->ext_table)->where('did', $ids)->delete();
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
        //删除推荐位的信息
        model('PositionData')->deleteByModeId($modeId, $ids);
    }

    //查询解析模型数据用以构造from表单
    public function getFieldList($modelId, $id = null)
    {

        $list = self::where('modelid', $modelId)->where('status', 1)->order('listorder asc,id asc')->column("name,title,remark,type,value,options,ifsystem,ifeditable,ifrequire,jsonrule");
        if (!empty($list)) {
            //编辑信息时查询出已有信息
            if ($id) {
                $modelInfo = Db::name('Model')->where('id', $modelId)->field('tablename,type')->find();
                $dataInfo = Db::name($modelInfo['tablename'])->where('id', $id)->find();
                //查询附表信息
                if ($modelInfo['type'] == 2 && !empty($dataInfo)) {
                    $dataInfoExt = Db::name($modelInfo['tablename'] . $this->ext_table)->where('did', $dataInfo['id'])->find();
                }
            }
            foreach ($list as &$value) {
                if (isset($value['ifsystem'])) {
                    if ($value['ifsystem']) {
                        $value['fieldArr'] = 'modelField';
                        if (isset($dataInfo[$value['name']])) {
                            $value['value'] = $dataInfo[$value['name']];
                        }
                    } else {
                        $value['fieldArr'] = 'modelFieldExt';
                        if (isset($dataInfoExt[$value['name']])) {
                            $value['value'] = $dataInfoExt[$value['name']];
                        }
                    }
                }
                //解析字段关联规则
                $dataRule = [];
                /*if ('' != $value['jsonrule']) {
                $dataRule = json_decode($value['jsonrule'], true);
                }*/
                if ('' != $value['options']) {
                    $value['options'] = parse_attr($value['options']);
                } elseif (isset($dataRule['choose'])) {
                    $value['options'] = Db::name($dataRule['choose']['table'])->where($dataRule['choose']['where'])->limit($dataRule['choose']['limit'])->order($dataRule['choose']['order'])->column($dataRule['choose']['key'] . ',' . $dataRule['choose']['value']);
                }
                if ('' == $value['value'] && isset($dataRule['string'])) {
                    $stringArray = Db::name($dataRule['string']['table'])->where($dataRule['string']['where'])->limit($dataRule['string']['limit'])->order($dataRule['string']['order'])->column($dataRule['string']['key']);
                    if (!empty($stringArray)) {
                        $value['value'] = implode($dataRule['string']['delimiter'], $stringArray);
                    }
                }
                if ($value['type'] == 'checkbox') {
                    $value['value'] = (strlen($value['value']) > 2) ? substr($value['value'], 1, -1) : '';
                    $value['value'] = empty($value['value']) ? [] : explode(',', $value['value']);
                }
                if ($value['type'] == 'datetime') {
                    $value['value'] = empty($value['value']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $value['value']);
                }
                if ($value['type'] == 'date') {
                    $value['value'] = empty($value['value']) ? '' : date('Y-m-d', $value['value']);
                }
                if ($value['type'] == 'image') {
                    $value['param'] = ['dir' => 'images', 'module' => 'admin'];
                    if (isset($dataRule['thumb']['ifon'])) {
                        $value['param']['thumb'] = 1;
                        $value['param']['thumbsize'] = $dataRule['thumb']['size'];
                        $value['param']['thumbtype'] = $dataRule['thumb']['type'];
                    }
                }
                if ($value['type'] == 'images') {
                    $value['param'] = ['dir' => 'images', 'module' => 'admin'];
                    if (isset($dataRule['thumb']['ifon'])) {
                        $value['param']['thumb'] = 1;
                        $value['param']['thumbsize'] = $dataRule['thumb']['size'];
                        $value['param']['thumbtype'] = $dataRule['thumb']['type'];
                    }
                    /*if (!empty($value['value'])) {
                $value['value'] .= ',';
                }*/
                }
                if ($value['type'] == 'file') {
                    $value['param'] = ['dir' => 'files', 'module' => 'admin'];
                    if (isset($dataRule['file']['type'])) {
                        $value['param']['sizelimit'] = $dataRule['file']['size'];
                        $value['param']['extlimit'] = $dataRule['file']['type'];
                    }
                }
                if ($value['type'] == 'files') {
                    $value['param'] = ['dir' => 'files', 'module' => 'admin'];
                    if (isset($dataRule['file']['type'])) {
                        $value['param']['sizelimit'] = $dataRule['file']['size'];
                        $value['param']['extlimit'] = $dataRule['file']['type'];
                    }
                    /*if (!empty($value['value'])) {
                $value['value'] .= ',';
                }*/
                }
                if ($value['type'] == 'Ueditor') {
                    $value['value'] = htmlspecialchars_decode($value['value']);

                }
                if ($value['type'] == 'summernote') {
                    $value['value'] = htmlspecialchars_decode($value['value']);
                }
            }
        }
        return $list;
    }

    //处理post提交的模型数据
    protected function dealModelPostData($modeId, $data, $dataExt = [], $ignoreField = [])
    {
        //字段类型
        $query = self::where('modelid', $modeId)->where('status', 1);
        if ([] != $ignoreField) {
            $query = $query->where('name', 'not in', $ignoreField);
        }
        $filedTypeList = $query->column('name,title,type,ifsystem,ifeditable,ifrequire');
        //字段规则
        $fieldRule = Db::name('field_type')->column('name', 'vrule');
        foreach ($filedTypeList as $name => $vo) {
            $arr = $vo['ifsystem'] ? 'data' : 'dataExt';
            if (!isset(${$arr}[$name])) {
                switch ($vo['type']) {
                    // 开关
                    case 'switch':
                        ${$arr}[$name] = 0;
                        break;
                    case 'checkbox':
                        ${$arr}[$name] = '';
                        break;
                }
            } else {
                //推荐位
                if ($name == 'posid') {
                    if (empty(${$arr}[$name]) || !is_array(${$arr}[$name])) {
                        ${$arr}[$name] = 0;
                    }
                    $number = count(${$arr}[$name]);
                    ${$arr}[$name] = $number == 1 ? 0 : 1;
                }
                if (is_array(${$arr}[$name])) {
                    ${$arr}[$name] = ',' . implode(',', ${$arr}[$name]) . ',';
                }
                switch ($vo['type']) {
                    // 开关
                    case 'switch':
                        ${$arr}[$name] = 1;
                        break;
                    // 日期+时间
                    case 'datetime':
                        if ($vo['ifeditable']) {
                            ${$arr}[$name] = strtotime(${$arr}[$name]);
                        }
                        break;
                    // 日期
                    case 'date':
                        ${$arr}[$name] = strtotime(${$arr}[$name]);
                        break;
                    case 'images':
                        if (!empty(${$arr}[$name])) {
                            $imageArr = explode(',', substr(${$arr}[$name], 0, -1));
                            $uniqueImageArr = array_unique($imageArr);
                            ${$arr}[$name] = implode(',', $uniqueImageArr);
                        }
                        break;
                    case 'files':
                        if (!empty(${$arr}[$name])) {
                            $fileArr = explode(',', substr(${$arr}[$name], 0, -1));
                            $uniqueFileArr = array_unique($fileArr);
                            ${$arr}[$name] = implode(',', $uniqueFileArr);
                        }
                        break;
                    // 百度编辑器
                    case 'Ueditor':
                        ${$arr}[$name] = htmlspecialchars(stripslashes(${$arr}[$name]));
                        break;
                    // 简洁编辑器
                    case 'summernote':
                        ${$arr}[$name] = htmlspecialchars(stripslashes(${$arr}[$name]));
                        break;
                }
            }
            //数据必填验证
            if ($vo['ifrequire'] && empty(${$arr}[$name])) {
                throw new \Exception("'" . $vo['title'] . "'必须填写~");
            }
            //数据格式验证
            if (!empty($fieldRule[$vo['type']]) && !empty(${$arr}[$name]) && !Validate::{$fieldRule[$vo['type']]}(${$arr}[$name])) {
                throw new \Exception("'" . $vo['title'] . "'格式错误~");
                //安全过滤
            } else {

            }
        }
        return [$data, $dataExt];
    }

    /**
     * 列表页
     * @param   $modeId  []
     * @param   $where   []
     * @param   $moreifo []
     * @param   $field   []
     * @param   $order   []
     * @param   $limit   []
     * @param   $page    []
     */
    public function getDataList($modeId, $where, $moreifo, $field = '*', $order = '', $limit, $page = null)
    {
        $modelCache = cache("Model");
        $ModelField = cache('ModelField');
        $tableName = $modelCache[$modeId]['tablename']; //表名

        if (2 == $modelCache[$modeId]['type'] && $moreifo) {
            $extTable = $tableName . $this->ext_table;
            if ($page) {
                $result = \think\Db::view(ucwords($tableName), '*')
                    ->where($where)
                    ->view($extTable, '*', $tableName . '.id=' . $extTable . '.did', 'LEFT')
                    ->order($order)
                    ->paginate($limit);
            } else {
                $result = \think\Db::view(ucwords($tableName), '*')
                    ->where($where)
                    ->limit($limit)
                    ->view($extTable, '*', $tableName . '.id=' . $extTable . '.did', 'LEFT')
                    ->order($order)
                    ->select();
            }
        } else {
            if ($page) {
                $result = \think\Db::name(ucwords($tableName))->where($where)->order($order)->paginate($limit);
            } else {
                $result = \think\Db::name(ucwords($tableName))->where($where)->limit($limit)->order($order)->select();
            }
        }
        //数据格式化处理
        if (!empty($result)) {
            foreach ($result as $key => $vo) {
                $vo = $this->dealModelShowData($ModelField[$modeId], $vo);
                $vo['url'] = buildContentUrl($vo['catid'], $vo['id']);
                $result[$key] = $vo;
            }
        }
        return $result;

    }

    /**
     * 详情页
     * @param  [type]  $modeId  []
     * @param  [type]  $where   []
     * @param  boolean $moreifo []
     * @param  string  $field   []
     * @param  string  $order   []
     */
    public function getDataInfo($modeId, $where, $moreifo = false, $field = '*', $order = '')
    {
        $modelInfo = cache('Model');
        $ModelField = cache('ModelField');
        if (false == $modelInfo || false == $ModelField) {
            return false;
        }
        $modelInfo = $modelInfo[$modeId];
        if (2 == $modelInfo['type'] && $moreifo) {
            $extTable = $modelInfo['tablename'] . $this->ext_table;
            $dataInfo = Db::view($modelInfo['tablename'], '*')->where($where)->view($extTable, '*', $modelInfo['tablename'] . '.id=' . $extTable . '.did', 'LEFT')->find();
        } else {
            $dataInfo = Db::name($modelInfo['tablename'])->field($field)->where($where)->find();
        }
        if (!empty($dataInfo)) {
            $dataInfo = $this->dealModelShowData($ModelField[$modeId], $dataInfo);
            $dataInfo['url'] = buildContentUrl($dataInfo['catid'], $dataInfo['id']);
        }

        return $dataInfo;
    }

    /**
     * 数据处理 前端显示
     * @param  $fieldinfo
     * @param  $data
     */
    protected function dealModelShowData($fieldinfo, $data)
    {
        $newdata = [];
        foreach ($data as $key => $value) {
            switch ($fieldinfo[$key]['type']) {
                case 'array':
                    $newdata[$key] = parse_attr($newdata[$key]);
                    break;
                case 'radio':

                    break;
                case 'select':

                    break;
                case 'checkbox':
                    break;
                case 'image':
                    $newdata[$key] = $value;
                    break;
                case 'images':
                    break;
                case 'files':
                    break;
                case 'tags':
                    $newdata[$key] = explode(',', $value);
                    break;
                case 'Ueditor':
                    $newdata[$key] = htmlspecialchars_decode($value);
                    break;
                case 'summernote':
                    $newdata[$key] = htmlspecialchars_decode($value);
                    break;
                default:
                    $newdata[$key] = $value;
                    break;
            }
            if ('posid' == $key) {
                $newdata[$key] = $value;
            }
            if (!isset($newdata[$key])) {
                $newdata[$key] = '';
            }
        }
        return $newdata;
    }

    /**
     * 自动获取简介
     */
    protected function description(&$data, $dataExt)
    {
        //自动提取摘要，如果有设置自动提取，且description为空，且有内容字段才执行
        if ($data['description'] == '' && isset($dataExt['content'])) {
            $content = $dataExt['content'];
            $data['description'] = str_cut(str_replace(array("\r\n", "\t", '&ldquo;', '&rdquo;', '&nbsp;'), '', strip_tags($content)), 200);
        }
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

    //生成模型字段缓存
    public function model_field_cache()
    {
        $cache = array();
        $modelList = Db::name("Model")->select();
        foreach ($modelList as $info) {
            $data = Db::name("ModelField")->where(array("modelid" => $info['id'], "status" => 1))->order("listorder ASC")->select();
            $fieldList = array();
            if (!empty($data) && is_array($data)) {
                foreach ($data as $rs) {
                    $fieldList[$rs['name']] = $rs;
                }
            }
            $cache[$info['id']] = $fieldList;
        }
        cache('ModelField', $cache);
        return $cache;
    }

}
