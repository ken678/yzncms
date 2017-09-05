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
use \think\Db;
use \think\Cache;
use \think\Loader;
/**
 * 栏目模型
 */
class Category extends Model
{
    protected $insert = ['module' => 'content'];

    //新增栏目
    public function addCategory($post)
    {
        if (empty($post)) {
            $this->error = '添加栏目数据不能为空！';
            return false;
        }
        $data = $post['info'];
        //栏目类型
        $data['type'] = (int)$post['type'];
        //栏目设置
        $data['setting'] = $post['setting'];
        //栏目拼音
        $catname = iconv('utf-8', 'gbk', $data['catname']);
        $letters = gbk_to_pinyin($catname);
        $data['letter'] = strtolower(implode('', $letters));

        //序列化setting数据
        $data['setting'] = serialize($data['setting']);

        //数据验证
        $validate = Loader::validate('Category');
        if(!$validate->scene('add')->check($data)){
            $this->error = $validate->getError();
            return false;
        }

        $catid = $this->allowField(true)->save($data);
        if ($catid) {
           cache('Category', NULL);
           return $catid;
        }else{
           $this->error = '栏目添加失败！';
           return false;

        }
    }

    //编辑栏目
    public function editCategory($post)
    {
        if (empty($post)) {
            $this->error = '编辑栏目数据不能为空！';
            return false;
        }
        $catid = $post['catid'];
        $data = $post['info'];
        //查询该栏目是否存在
        $info = $this->where(array('catid' => $catid))->find();
        if (empty($info)) {
            $this->error = '该栏目不存在！';
            return false;
        }
        unset($data['catid'], $info['catid'], $data['module'], $data['child']);

        //栏目设置
        $data['setting'] = $post['setting'];
        //栏目拼音
        $catname = iconv('utf-8', 'gbk', $data['catname']);
        $letters = gbk_to_pinyin($catname);
        $data['letter'] = strtolower(implode('', $letters));

        //序列化setting数据
        $data['setting'] = serialize($data['setting']);
        //数据验证
        $validate = Loader::validate('Category');
        if(!$validate->scene('edit')->check($data)){
            $this->error = $validate->getError();
            return false;
        }
        $catid = $this->isUpdate(true)->allowField(true)->save($data,['catid' => $catid]);
        if ($catid) {
           cache('Category', NULL);
           return $catid;
        }else{
           $this->error = '栏目添加失败！';
           return false;
        }
    }


    /**
     * 删除栏目
     */
    public function deleteCatid($catid)
    {
        $where = array();
        $where['catid'] = $catid;
        $catInfo = $this->where($where)->find();
        //是否存在子栏目
        if ($catInfo['child'] && $catInfo['type'] == 0) {
            $arrchildid = explode(",", $catInfo['arrchildid']);
            unset($arrchildid[0]);
            $catid = array_merge($arrchildid, array($catid));
            $where['catid'] = array("IN", $catid);
        }
        if (is_array($catid)) {
              $modeid = array();
              foreach ($catid as $cid) {
                  $catinfo = getCategory($cid);
                  if ($catinfo['modelid'] && $catinfo['type'] == 0) {
                      $modeid[$catinfo['modelid']] = $catinfo['modelid'];
                  }
                  foreach ($modeid as $mid) {
                      $tbname = ucwords(getModel($mid, 'tablename'));
                      if ($tbname && Db::name($tbname)->where(array("catid" => array("IN", $catid)))->find()) {
                          return false;
                      }
                  }
              }
        }else{
              $catinfo = getCategory($catid);
              $tbname = ucwords(getModel($catInfo['modelid'], 'tablename'));
              if ($tbname && $catinfo['type'] == 0 && Db::name($tbname)->where(array("catid" => $catid))->find()) {
                  return false;
              }
        }
        $status = $this->where($where)->delete();
        //更新缓存
        cache('Category', NULL);
        if (false !== $status) {
            //TD
            return true;
        } else {
            return false;
        }
    }

    //刷新栏目索引缓存
    public function category_cache()
    {
        $data = Db::name('category')->order("listorder ASC")->select();
        $CategoryIds = array();
        foreach ($data as $r) {
            $CategoryIds[$r['catid']] = array(
                'catid' => $r['catid'],
                'parentid' => $r['parentid'],
            );
        }
        Cache::set("Category", $CategoryIds);
        return $CategoryIds;
    }


}