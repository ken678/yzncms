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
// | Tag模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use \think\Model;

/**
 * Tag模型
 */
class Tags extends Model
{
    /**
     * 添加tags
     * @param type $tagname tags名称 可以是数组
     * @param type $id 信息id
     * @param type $catid 栏目Id
     * @param type $modelid 模型id
     */
    public function addTag($tagname, $id, $catid, $modelid)
    {
        if (!$tagname || !$id || !$catid || !$modelid) {
            return false;
        }
        var_dump(11);
        exit();

    }

    /**
     * 根据指定的条件更新tags数据
     * @param type $tagname
     * @param type $id
     * @param type $catid
     * @param type $modelid
     */
    public function updata($tagname, $id, $catid, $modelid)
    {
        if (!$tagname || !$id || !$catid || !$modelid) {
            return false;
        }
        $tags = model("TagsContent")->where(array(
            "modelid" => $modelid,
            "contentid" => $id,
            "catid" => $catid,
        ))->select();
        foreach ($tags as $key => $value) {

        }

        var_dump($tags);
        exit();

    }

    /**
     * 根据信息id删除全部的tags记录
     * @param type $id
     * @param type $catid
     * @param type $modelid
     * @return boolean
     */
    public function deleteAll($id, $catid, $modelid)
    {
        if (!$id || !$catid || !$modelid) {
            return false;
        }
        /*$db_tags_content = M("TagsContent");
        $where = array('modelid' => $modelid, 'contentid' => $id, "catid" => $catid);
        //取得对应tag数据
        $tagslist = $db_tags_content->where($where)->select();
        if (empty($tagslist)) {
        return true;
        }
        //全部-1
        foreach ($tagslist as $k => $value) {
        $this->where(array("tag" => $value['tag']))->setDec('usetimes');
        }
        //删除tags数据
        $db_tags_content->where($where)->delete();*/
        return true;
    }

}
