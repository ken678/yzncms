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
// | CMS模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use app\cms\library\FulltextSearch;
use app\common\model\Modelbase;
use think\Db;
use think\facade\Validate;
use think\Model;

/**
 * 模型
 */
class Cms extends Modelbase
{
    protected $autoWriteTimestamp = true;
    protected $insert             = ['status' => 1];
    protected $ext_table          = '_data';
    protected $name               = 'ModelField';

    /**
     * 根据模型ID，返回表名
     * @param type $modelid
     * @param type $modelid
     * @return string
     */
    protected function getModelTableName($modelid, $ifsystem = 1)
    {
        $model_cache = cache("Model");
        //表名获取
        $model_table = isset($model_cache[$modelid]['tablename']) ? ucwords($model_cache[$modelid]['tablename']) : '';
        //完整表名获取 判断主表 还是副表
        $tablename = $ifsystem ? $model_table : $model_table . $this->ext_table;
        return $tablename;
    }

    //添加模型内容
    public function addModelData($data, $dataExt = [])
    {
        $catid = (int) $data['catid'];
        if (isset($data['modelid'])) {
            $modelid = $data['modelid'];
            unset($data['modelid']);
        } else {
            $modelid = getCategory($catid, 'modelid');
        }
        //完整表名获取
        $tablename = $this->getModelTableName($modelid);
        if (!$this->table_exists($tablename)) {
            throw new \Exception('数据表不存在！');
        }
        $this->getAfterText($data, $dataExt);
        if (!defined('IN_ADMIN') || (defined('IN_ADMIN') && IN_ADMIN == false)) {
            empty($data['uid']) ? \app\member\service\User::instance()->id : $data['uid'];
            empty($data['username']) ? \app\member\service\User::instance()->username : $data['username'];
            $data['sysadd'] = 0;
        } else {
            //添加用户名
            $data['uid']      = \app\admin\service\User::instance()->id;
            $data['username'] = \app\admin\service\User::instance()->username;
            $data['sysadd']   = 1;
        }
        //处理数据
        $dataAll              = $this->dealModelPostData($modelid, $data, $dataExt);
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
            //TAG标签处理
            if (!empty($data['tags'])) {
                $this->tagDispose($data['tags'], $id, $catid, $modelid);
            }
            //附表
            if (!empty($dataExt)) {
                $dataExt['did'] = $id;
                Db::name($tablename . $this->ext_table)->insert($dataExt);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        //更新栏目统计数据
        $this->updateCategoryItems($catid, 'add', 1);
        //推送到熊掌号和百度站长
        $cmsConfig = cache("Cms_Config");
        if (isset($cmsConfig['web_site_baidupush']) && $cmsConfig['web_site_baidupush']) {
            hook("baidupush", buildContentUrl($catid, $id, $data['url'], true, true));
        }
        //新增讯搜索引
        if (isset(cache("Cms_Config")['web_site_searchtype']) && cache("Cms_Config")['web_site_searchtype'] === 'xunsearch') {
            FulltextSearch::update($modelid, $catid, $id, $data, $dataExt);
        }
        return $id;
    }

    //编辑模型内容
    public function editModelData($data, $dataExt = [])
    {
        $catid   = (int) $data['catid'];
        $id      = (int) $data['id'];
        $modelid = getCategory($catid, 'modelid');
        //完整表名获取
        $tablename = $this->getModelTableName($modelid);
        if (!$this->table_exists($tablename)) {
            throw new \Exception('数据表不存在！');
        }
        $this->getAfterText($data, $dataExt);
        //TAG标签处理
        if (!empty($data['tags'])) {
            $this->tagDispose($data['tags'], $id, $catid, $modelid);
        } else {
            $this->tagDispose([], $id, $catid, $modelid);
        }
        $dataAll              = $this->dealModelPostData($modelid, $data, $dataExt);
        list($data, $dataExt) = $dataAll;

        if (!isset($data['updatetime'])) {
            $data['updatetime'] = request()->time();
        }
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
        //标签
        hook('content_edit_end', $data);
        //更新讯搜索引
        if (isset(cache("Cms_Config")['web_site_searchtype']) && cache("Cms_Config")['web_site_searchtype'] === 'xunsearch') {
            FulltextSearch::update($modelid, $catid, $id, $data, $dataExt);
        }
    }

    //删除模型内容
    public function deleteModelData($modeId, $id, $no_delete = false)
    {
        $modelInfo = cache('Model');
        $modelInfo = $modelInfo[$modeId];

        $data = Db::name($modelInfo['tablename'])->where('id', $id)->find();
        if (empty($data)) {
            throw new \Exception("该信息不存在！");
        }
        //处理tags
        if (!empty($data['tags'])) {
            $this->tagDispose([], $data['id'], $data['catid'], $modeId);
        }

        if ($no_delete) {
            Db::name($modelInfo['tablename'])->where('id', $id)->setField('status', -1);
        } else {
            Db::name($modelInfo['tablename'])->where('id', $id)->delete();
            if (2 == $modelInfo['type']) {
                Db::name($modelInfo['tablename'] . $this->ext_table)->where('did', $id)->delete();
            }
            //更新栏目统计
            $this->updateCategoryItems($data['catid'], 'delete');
        }
        //标签
        hook('content_delete_end', $data);
        //更新讯搜索引
        if (isset(cache("Cms_Config")['web_site_searchtype']) && cache("Cms_Config")['web_site_searchtype'] === 'xunsearch') {
            FulltextSearch::del($data['catid'], $id);
        }
    }

    //处理post提交的模型数据
    protected function dealModelPostData($modeId, $data, $dataExt = [], $ignoreField = [])
    {
        //字段类型
        $query = self::where('modelid', $modeId)->where('status', 1);
        if ([] != $ignoreField) {
            $query = $query->where('name', 'not in', $ignoreField);
        }
        $filedTypeList = $query->order('listorder DESC, id DESC')->column('name,title,type,ifsystem,ifrequire,pattern,errortips');

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
                if (is_array(${$arr}[$name])) {
                    ${$arr}[$name] = implode(',', ${$arr}[$name]);
                }
                switch ($vo['type']) {
                    // 开关
                    case 'switch':
                        ${$arr}[$name] = 1;
                        break;
                    // 日期+时间
                    case 'datetime':
                        //if ($vo['ifeditable']) {
                        ${$arr}[$name] = strtotime(${$arr}[$name]);
                        //}
                        break;
                    // 日期
                    case 'date':
                        ${$arr}[$name] = strtotime(${$arr}[$name]);
                        break;
                    // 编辑器
                    case 'markdown':
                    case 'Ueditor':
                        ${$arr}[$name] = htmlspecialchars(stripslashes(${$arr}[$name]));
                        break;
                }
            }
            //数据必填验证
            if ($vo['ifrequire'] && (!isset(${$arr}[$name]) || ${$arr}[$name] == '')) {
                throw new \Exception("'" . $vo['title'] . "'必须填写~");
            }
            //正则校验
            if (isset(${$arr}[$name]) && ${$arr}[$name] && $vo['pattern'] && !Validate::regex(${$arr}[$name], $vo['pattern'])) {
                throw new \Exception("'" . $vo['title'] . "'" . (!empty($vo['errortips']) ? $vo['errortips'] : '正则校验失败') . "");
            }
            //数据格式验证
            if (!empty(${$arr}[$name]) && in_array($vo['type'], ['number']) && !Validate::isNumber(${$arr}[$name])) {
                throw new \Exception("'" . $vo['title'] . "'格式错误~");
                //安全过滤
            } else {

            }
        }
        return [$data, $dataExt];
    }

    //查询解析模型数据用以构造from表单
    public function getFieldList($modelId, $id = null)
    {

        $list = self::where('modelid', $modelId)->where('status', 1)->order('listorder DESC,id DESC')->column("name,title,remark,type,isadd,iscore,ifsystem,ifrequire,setting");
        if (!empty($list)) {
            //编辑信息时查询出已有信息
            if ($id) {
                $modelInfo = Db::name('Model')->where('id', $modelId)->field('tablename,type')->find();
                $dataInfo  = Db::name($modelInfo['tablename'])->where('id', $id)->find();
                //查询附表信息
                if ($modelInfo['type'] == 2 && !empty($dataInfo)) {
                    $dataInfoExt = Db::name($modelInfo['tablename'] . $this->ext_table)->where('did', $dataInfo['id'])->find();
                }
            }
            foreach ($list as $key => &$value) {
                //内部字段不显示
                if ($value['iscore']) {
                    unset($list[$key]);
                }
                //核心字段做标记
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

                //扩展配置
                $value['setting'] = unserialize($value['setting']);
                $value['options'] = $value['setting']['options'] ?? '';
                //在新增时候添加默认值
                if (!$id) {
                    $value['value'] = $value['setting']['value'] ?? '';
                }
                if ($value['type'] == 'custom') {
                    if ($value['options'] != '') {
                        $tpar             = explode(".", $value['options'], 2);
                        $value['options'] = \think\Response::create('admin@custom/' . $tpar[0], 'view')->assign('vo', $value)->getContent();
                        unset($tpar);
                    }
                } elseif ($value['options'] != '') {
                    $value['options'] = parse_attr($value['options']);
                }
                /*if ('' != $value['options']) {
                $value['options'] = parse_attr($value['options']);
                }*/
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

    /**
     * 列表页
     * @param   $modeId  [模型ID]
     * @param   $where   [查询条件]
     * @param   $moreifo [是否含附表]
     * @param   $field   []
     * @param   $order   []
     * @param   $limit   [条数]
     * @param   $page    [是否有分页]
     * @param  int|bool  $simple   是否简洁模式或者总记录数
     * @param  array     $config   配置参数
     */
    public function getList($modeId, $where, $moreifo, $field = '*', $order = '', $limit = 10, $page = null, $simple = false, $config = [])
    {
        $url_mode  = isset(cache("Cms_Config")['site_url_mode']) ? cache("Cms_Config")['site_url_mode'] : 1;
        $tableName = $this->getModelTableName($modeId);
        $result    = [];
        if (isset($tableName) && !empty($tableName)) {
            if (2 == getModel($modeId, 'type') && $moreifo) {
                $extTable = $tableName . $this->ext_table;
                $cmsModel = Db::view($tableName, $field)->where($where)->view($extTable, '*', $tableName . '.id=' . $extTable . '.did', 'LEFT')->order($order);
                if ($page) {
                    $result = $cmsModel->paginate($limit, $simple, $config);
                } else {
                    $result = $cmsModel->limit($limit)->select();
                }
            } else {
                $cmsModel = Db::name($tableName)->where($where)->order($order)->field($field);
                if ($page) {
                    $result = $cmsModel->paginate($limit, $simple, $config);
                } else {
                    $result = $cmsModel->limit($limit)->select();
                }
            }
        }
        //数据格式化处理
        if (!empty($result)) {
            $ModelField = cache('ModelField');
            $Category   = cache('Category');
            foreach ($result as $key => $vo) {
                $vo           = $this->dealModelShowData($ModelField[$modeId], $vo);
                $cat          = $url_mode == 1 ? $vo['catid'] : (isset($Category[$vo['catid']]) ? $Category[$vo['catid']]['catdir'] : getCategory($vo['catid'], 'catdir'));
                $vo['url']    = buildContentUrl($cat, $vo['id'], $vo['url']);
                $result[$key] = $vo;
            }
        }
        return $result;
    }

    /**
     * 详情页
     * @param  [type]  $modeId  [模型ID]
     * @param  [type]  $where   [查询条件]
     * @param  boolean $moreifo [是否含附表]
     * @param  string  $field   []
     * @param  string  $order   []
     */
    public function getContent($modeId, $where, $moreifo = false, $field = '*', $order = '', $cache = false)
    {
        $url_mode  = isset(cache("Cms_Config")['site_url_mode']) ? cache("Cms_Config")['site_url_mode'] : 1;
        $tableName = $this->getModelTableName($modeId);
        if (2 == getModel($modeId, 'type') && $moreifo) {
            $extTable = $tableName . $this->ext_table;
            $dataInfo = Db::view($tableName, '*')->where($where)->cache($cache)->view($extTable, '*', $tableName . '.id=' . $extTable . '.did', 'LEFT')->find();
        } else {
            $dataInfo = Db::name($tableName)->field($field)->cache($cache)->where($where)->order($order)->find();
        }
        if (!empty($dataInfo)) {
            $ModelField      = cache('ModelField');
            $Category        = cache('Category');
            $dataInfo        = $this->dealModelShowData($ModelField[$modeId], $dataInfo);
            $cat             = $url_mode == 1 ? $dataInfo['catid'] : (isset($Category[$dataInfo['catid']]) ? $Category[$dataInfo['catid']]['catdir'] : getCategory($dataInfo['catid'], 'catdir'));
            $dataInfo['url'] = buildContentUrl($cat, $dataInfo['id'], $dataInfo['url']);
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
                    $newdata[$key] = (array) json_decode($value, true);
                    break;
                case 'radio':
                case 'select':
                    if (!empty($value)) {
                        if (!empty($fieldinfo[$key]['options'])) {
                            $optionArr               = parse_attr($fieldinfo[$key]['options']);
                            $newdata[$key . '_text'] = isset($optionArr[$value]) ? $optionArr[$value] : $value;
                        }
                    }
                    $newdata[$key] = $value;
                    break;
                case 'selects':
                case 'checkbox':
                    if (!empty($value)) {
                        if (!empty($fieldinfo[$key]['options'])) {
                            $optionArr = parse_attr($fieldinfo[$key]['options']);
                            $valueArr  = explode(',', $value);
                            foreach ($valueArr as $v) {
                                if (isset($optionArr[$v])) {
                                    $newdata[$key][$v] = $optionArr[$v];
                                } elseif ($v) {
                                    $newdata[$key][$v] = $v;
                                }
                            }
                            //其他表关联
                        } else {
                            $newdata[$key] = [];
                        }
                    }
                    break;
                case 'files':
                case 'images':
                    $newdata[$key] = empty($value) ? [] : explode(',', $value);
                    break;
                /*case 'tags':
                $newdata[$key] = empty($value) ? [] : explode(',', $value);
                break;*/
                case 'markdown':
                    $parser        = new \util\Parser;
                    $newdata[$key] = $parser->makeHtml(htmlspecialchars_decode($value));
                    break;
                case 'Ueditor':
                    $newdata[$key] = htmlspecialchars_decode($value);
                    break;
                default:
                    $newdata[$key] = $value;
                    break;
            }
            if (!isset($newdata[$key])) {
                $newdata[$key] = '';
            }
        }
        return $newdata;
    }

    /**
     * TAG标签处理
     */
    private function tagDispose($tags, $id, $catid, $modelid)
    {
        $tags_mode = model('cms/Tags');
        if (!empty($tags)) {
            if (strpos($tags, ',') === false) {
                $keyword = explode(' ', $tags);
            } else {
                $keyword = explode(',', $tags);
            }
            $keyword = array_unique($keyword);
            if ('add' == request()->action()) {
                $tags_mode->addTag($keyword, $id, $catid, $modelid);
            } else {
                $tags_mode->updata($keyword, $id, $catid, $modelid);
            }

        } else {
            //直接清除已有的tags
            $tags_mode->deleteAll($id, $catid, $modelid);
        }
    }

    /**
     * 文本处理
     */
    protected function getAfterText(&$data, &$dataExt)
    {
        //自动提取摘要，如果有设置自动提取，且description为空，且有内容字段才执行
        if (isset($data['get_introduce']) && $data['description'] == '' && (isset($dataExt['content']) || isset($data['content']))) {
            $content             = isset($dataExt['content']) ? $dataExt['content'] : (isset($data['content']) ? $data['content'] : '');
            $data['description'] = mb_substr(str_replace(array("\r\n", "\t", '&ldquo;', '&rdquo;', '&nbsp;'), '', strip_tags($content)), 0, 200);
        }
        //自动提取缩略图
        if (isset($data['auto_thumb']) && empty($data['thumb']) && (isset($dataExt['content']) || isset($data['content']))) {
            $thumb         = isset($dataExt['content']) ? \util\GetImgSrc::src($dataExt['content']) : (isset($data['content']) ? \util\GetImgSrc::src($data['content']) : false);
            $data['thumb'] = $thumb ? $thumb : '';
        }
        //关键词加链接
        $autolinks = cache("Cms_Config")['autolinks'];
        if (!empty($autolinks) && isset($dataExt['content'])) {
            if (strpos($autolinks, '|')) {
                //解析关键词数组
                $kwsets = array_filter(preg_split("/(\r|\n|\r\n)/", $autolinks));
                foreach ($kwsets as $kwset) {
                    $kwarray[] = explode('|', $kwset);
                }
            }
            foreach ($kwarray as $i => $row) {
                $txt  = isset($row[0]) ? trim($row[0]) : '';
                $link = isset($row[1]) ? trim($row[1]) : '';
                $set  = isset($row[2]) ? trim($row[2]) : '';
                if ($txt && $link) {
                    $rel  = '';
                    $open = '_blank';

                    //处理标记与打开方式
                    if ($set) {
                        if (false !== stripos($set, 'e')) {
                            $rel = ' rel="external nofollow"';
                        } elseif (false !== stripos($set, 'n')) {
                            $rel = ' rel="nofollow"';
                        }
                        $open = false !== stripos($set, 'b') ? '_self' : $open;
                    }

                    $dataExt['content'] = false !== strpos($dataExt['content'], $txt)
                    //正则排除参数和链接
                     ? preg_replace('/(?!<[^>]*)' . $txt . '(?![^<]*(>|<\/[a|sc]))/s'
                        , '<a href="' . $link . '"' . $rel . 'target="' . $open . '" title="' . $txt . '">' . $txt . '</a>', $dataExt['content']) : $dataExt['content'];
                }
            }
        }
        unset($data['get_introduce']);
        unset($data['auto_thumb']);
    }

    private function updateCategoryItems($catid, $action = 'add', $cache = 0)
    {
        if ($action == 'add') {
            Db::name('Category')->where('id', $catid)->setInc('items');
        } else {
            Db::name('Category')->where('id', $catid)->where('items', '>', 0)->setDec('items');
        }
    }

    //会员配置缓存
    public function cms_cache()
    {
        $data = unserialize(model('admin/Module')->where(array('module' => 'cms'))->value('setting'));
        cache("Cms_Config", $data);
        return $data;
    }
}
