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

use think\Model;

/**
 * 单页模型
 */
class Page extends Model
{
    /**
     * 根据栏目ID获取内容
     * @param type $catid 栏目ID
     * @return boolean
     */
    public function getPage($catid)
    {
        if (empty($catid)) {
            return false;
        }
        return $this->where(array('catid' => $catid))->find();
    }

    /**
     * 更新单页内容
     * @param type $post 表单数据
     * @return boolean
     */
    public function savePage($post)
    {
        if (empty($post)) {
            $this->error = '内容不能为空！';
            return false;
        }
        $data = $post['info'];
        $catid = $data['catid'];
        //单页内容
        $info = $this->where(array('catid' => $catid))->find();
        if ($info) {
            unset($data['catid']);
        }
        //$data = $this->token(false)->create($data, isset($data['catid']) ? 1 : 2);

        //取得标题颜色
        if (isset($post['style_color'])) {
            //颜色选择为隐藏域 在这里进行取值
            $data['style'] = $post['style_color'] ? strip_tags($post['style_color']) : '';
            //标题加粗等样式
            if (isset($post['style_font_weight'])) {
                $data['style'] = $data['style'] . ($post['style_font_weight'] ? ';' : '') . strip_tags($post['style_font_weight']);
            }
        }
        //新增或修改
        if ($info) {
            if ($this->allowField(true)->isUpdate(true)->save($data, ['catid' => $catid]) !== false) {
                return true;
            }
        } else {
            if ($this->allowField(true)->save($data) !== false) {
                return true;
            }
        }
        $this->error = '操作失败！';
        return false;

    }
}
