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
// | 单页模型
// +----------------------------------------------------------------------
namespace app\admin\model\cms;

use think\Model;

class Page extends Model
{

    protected $pk = 'catid';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'inputtime';

    protected $updateTime = 'updatetime';

    protected function setInputTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    /**
     * 根据栏目ID获取内容
     *
     * @param  type  $catid  栏目ID
     *
     * @return boolean
     */
    public function getPage($catid, $cache = false)
    {
        if (empty($catid)) {
            return false;
        }
        if (is_numeric($cache)) {
            $cache = (int) $cache;
        }
        return self::get($catid, $cache);
    }

    /**
     * 更新单页内容
     *
     * @param $data
     *
     * @return boolean
     */
    public function savePage($data)
    {
        if (empty($data)) {
            $this->error = '内容不能为空！';
            return false;
        }
        $catid = $data['catid'];
        $row   = self::get($catid);
        if ($row) {
            //更新
            self::update($data, [], true);
        } else {
            //新增
            self::create($data, true);
        }
        return true;
    }

}
