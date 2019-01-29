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
namespace app\cms\taglib;

use think\facade\Cache;
use think\template\TagLib;

class Yzn extends Taglib
{

    // 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        //上一篇
        'pre' => ['attr' => 'catid,id,target,msg,field', 'close' => 0],
        //下一篇
        'next' => ['attr' => 'catid,id,target,msg,field', 'close' => 0],
        //导航标签
        'category' => ['attr' => 'catid,cache,num,return,where,order', 'close' => 1, 'level' => 3],
    ];

    protected function replaceVar($str)
    {
        if (preg_match_all("/(?:\[)(.*)(?:\])/iU", $str, $matches)) {
            foreach ($matches[1] as $key => $vo) {
                $realVal = $this->autoBuildVar($vo);
                //不在开头
                if (0 != strpos($str, $matches[0][$key])) {
                    $realVal = '".' . $realVal;
                }
                //不在结尾
                if (strpos($str, $matches[0][$key]) < strlen($str) - strlen($matches[0][$key])) {
                    $realVal = $realVal . '."';
                }
                $str = str_replace($matches[0][$key], $realVal, $str);
            }
        }
        return $str;
    }

    public function tagCategory($tag, $content)
    {
        //每页显示总数
        $num = isset($tag['num']) ? (int) $tag['num'] : 10;
        //数据返回变量
        $return = isset($tag['return']) ? "data" : $tag['return'];
        $where = isset($tag['where']) ? $tag['where'] : "status='1'";
        $order = isset($tag['order']) ? $tag['order'] : 'listorder,id desc';
        //缓存时间
        $cache = (int) $tag['cache'];
        $where = $this->replaceVar($where);

        $cacheID = to_guid_string($tag);
        if ($cache && $array = Cache::get($cacheID)) {
            return $array;
        }

        if (isset($tag['catid'])) {
            $catid = (int) $tag['catid'];
            //$where['parentid'] = $tag['catid'];
        }

        //拼接php代码
        $parseStr = '<?php ';
        $parseStr .= '$db = model("Category");';
        //如果条件不为空，进行查库
        if (!empty($where)) {
            $parseStr .= '$' . $return . '= $db->where("' . $where . '")->limit(' . $num . ')->select();';
        }
        $parseStr .= 'foreach($' . $return . ' as $key=>$vo): ?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; ?>';
        return $parseStr;
    }

}
