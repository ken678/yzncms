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
use think\Db;
use think\Model;

/**
 * 菜单基础模型
 */
class ModelField extends Model
{
    //不显示的字段类型（字段类型）
    public $not_allow_fields = array('catid', 'typeid', 'title', 'keyword', 'template', 'username', 'tags');

    //允许添加但必须唯一的字段（字段名）
    public $unique_fields = array('pages', 'readpoint', 'author', 'copyfrom', 'islink', 'posid');

     //禁止被禁用（隐藏）的字段列表（字段名）
    public $forbid_fields = array('catid', 'title', /* 'updatetime', 'inputtime', 'url', 'listorder', 'status', 'template', 'username', 'allow_comment', 'tags' */);

    //禁止被删除的字段列表（字段名）
    public $forbid_delete = array('catid', 'title', 'thumb', 'keyword', 'keywords', 'updatetime', 'tags', 'inputtime', 'posid', 'url', 'listorder', 'status', 'template', 'username', 'allow_comment');

    //可以追加 JS和CSS 的字段（字段名）
    public $att_css_js = array('text', 'textarea', 'box', 'number', 'keyword', 'typeid');
    /**
     * 根据模型ID读取全部字段信息
     */
    public function getModelField($modelid) {
        return $this->where(array("modelid" => $modelid))->order(array("listorder" => "ASC"))->select();
    }

    //生成模型字段缓存
    public function model_field_cache() {
        $cache = array();
        $modelList = Db::name("Model")->select();
        foreach ($modelList as $info) {
            $data = Db::name("ModelField")->where(array("modelid" => $info['modelid'], "disabled" => 0))->order(" listorder ASC ")->select();
            $fieldList = array();
            if (!empty($data) && is_array($data)) {
                foreach ($data as $rs) {
                    $fieldList[$rs['field']] = $rs;
                }
            }
            $cache[$info['modelid']] = $fieldList;
        }
        cache('ModelField', $cache);
        return $cache;
    }


}