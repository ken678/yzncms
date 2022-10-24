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

use think\Model;

class Tags extends Model
{

    protected $autoWriteTimestamp = true;

    /**
     * 添加tags
     *
     * @param  type  $tagname  tags名称 可以是数组
     * @param  type  $id       信息id
     * @param  type  $catid    栏目Id
     * @param  type  $modelid  模型id
     *
     * @return false|void
     * @throws \think\Exception
     */
    public function addTag($tagname, $id, $catid, $modelid)
    {
        if (!$tagname || !$id || !$catid || !$modelid) {
            return false;
        }
        $time    = time();
        $newdata = [];
        if (is_array($tagname)) {
            foreach ($tagname as $v) {
                if (empty($v) || $v == '') {
                    continue;
                }
                $row = $this->where("tag", $v)->find();
                if ($row) {
                    $row->setInc('usetimes');
                } else {
                    self::create([
                        "tag"      => $v,
                        "usetimes" => 1,
                    ]);
                }
                $newdata[] = [
                    'tag'        => $v,
                    "modelid"    => $modelid,
                    "contentid"  => $id,
                    "catid"      => $catid,
                    "updatetime" => $time,
                ];
            }
            (new TagsContent())->saveAll($newdata);
        }
    }

    /**
     * 根据指定的条件更新tags数据
     *
     * @param  type  $tagname
     * @param  type  $id
     * @param  type  $catid
     * @param  type  $modelid
     *
     * @return false|void
     * @throws \think\Exception
     */
    public function updata($tagname, $id, $catid, $modelid)
    {
        if (!$tagname || !$id || !$catid || !$modelid) {
            return false;
        }
        $tags = TagsContent::where([
            "modelid"   => $modelid,
            "contentid" => $id,
            "catid"     => $catid,
        ])->select();
        foreach ($tags as $key => $val) {
            if (!$val) {
                continue;
            }
            //如果在新的关键字数组找不到，说明已经去除
            if (!in_array($val['tag'], $tagname)) {
                //删除不存在的tag
                $this->deleteTagName($val['tag'], $id, $catid, $modelid);
            } else {
                foreach ($tagname as $k => $v) {
                    if ($val['tag'] == $v) {
                        unset($tagname[$k]);
                    }
                }
            }
        }
        //新增的tags
        if (count($tagname) > 0) {
            $this->addTag($tagname, $id, $catid, $modelid);
        }
    }

    /**
     * 删除tag
     *
     * @param  type  $tagname
     * @param  type  $id
     * @param  type  $catid
     * @param  type  $modelid
     *
     * @return bool
     * @throws \think\Exception
     */
    public function deleteTagName($tagname, $id, $catid, $modelid)
    {
        if (!$id || !$catid || !$modelid || !$tagname) {
            return false;
        }
        if (is_array($tagname)) {
            foreach ($tagname as $name) {
                $row = $this->where("tag", $name)->find();
                if ($row) {
                    if ($row->usetimes > 0) {
                        $row->setDec('usetimes');
                    }
                    //删除tags数据
                    TagsContent::where(["tag" => $name, 'contentid' => $id, "catid" => $catid])->delete();
                }
            }
        } else {
            $row = $this->where("tag", $tagname)->find();
            if ($row) {
                if ($row->usetimes > 0) {
                    $row->setDec('usetimes');
                }
                //删除tags数据
                TagsContent::where(["tag" => $row['tag'], 'contentid' => $id, "catid" => $catid])->delete();
            }
        }
        return true;
    }

    /**
     * 根据信息id删除全部的tags记录
     *
     * @param  type  $id
     * @param  type  $catid
     * @param  type  $modelid
     *
     * @return boolean
     * @throws \think\Exception
     */
    public function deleteAll($id, $catid, $modelid)
    {
        if (!$id || !$catid || !$modelid) {
            return false;
        }
        $where = ['modelid' => $modelid, 'contentid' => $id, "catid" => $catid];
        //取得对应tag数据
        $tagslist = TagsContent::where($where)->select();
        if (empty($tagslist)) {
            return true;
        }
        //全部-1
        foreach ($tagslist as $k => $value) {
            $row = $this->where("tag", $value['tag'])->find();
            if ($row && $row->usetimes > 0) {
                $row->setDec('usetimes');
            }
        }
        //删除tags数据
        TagsContent::where($where)->delete();
        return true;
    }

}
