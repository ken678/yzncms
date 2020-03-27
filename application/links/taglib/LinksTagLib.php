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
// | 标签库
// +----------------------------------------------------------------------
namespace app\links\taglib;

use think\Db;

class LinksTagLib
{
    /**
     * 栏目标签
     */
    public function lists($data)
    {
        $where = isset($data['where']) ? $data['where'] : "status=1";
        $order = isset($data['order']) ? $data['order'] : 'listorder,id desc';
        $num = isset($data['num']) ? (int) $data['num'] : 10;
        if (isset($data['linktype'])) {
            $where .= empty($where) ? "linktype = " . (int) $data['linktype'] : " AND linktype = " . (int) $data['linktype'];
        }
        if (isset($data['typeid'])) {
            $where .= empty($where) ? "termsid = " . (int) $data['typeid'] : " AND termsid = " . (int) $data['typeid'];
        }
        $result = Db::name('Links')->where($where)->limit($num)->order($order)->withAttr('image', function ($value, $data) {
            return get_file_path($value);
        })->select();
        return $result;
    }

}
