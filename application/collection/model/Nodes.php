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
// | 采集模型
// +----------------------------------------------------------------------
namespace app\collection\model;

use \think\Model;

class Nodes extends Model
{
    protected $name = 'collection_node';
    public function getLastdateAttr($value)
    {
        return date("Y-m-d h:i:s", $value);
    }
    //创建
    public function addNode($data)
    {
        if (empty($data)) {
            throw new \Exception('数据不得为空！');
        }
        $_data            = $data['data'];
        $customize_config = isset($data['customize_config']) ? $data['customize_config'] : '';
        $_data['urlpage'] = isset($data['urlpage' . $_data['sourcetype']]) ? $data['urlpage' . $_data['sourcetype']] : '';
        self::allowField(true)->save($_data);
    }

    //编辑
    public function editNode($data)
    {
        if (empty($data)) {
            throw new \Exception('数据不得为空！');
        }
        $_data  = $data['data'];
        $nodeid = $_data['id'];
        $info   = self::where('id', $nodeid)->find();
        if ($info) {
            $customize_config = isset($data['customize_config']) ? $data['customize_config'] : '';
            $_data['urlpage'] = isset($data['urlpage' . $_data['sourcetype']]) ? $data['urlpage' . $_data['sourcetype']] : '';
            self::allowField(true)->save($_data, ['id' => $nodeid]);
            return true;
        }
        throw new \Exception('采集任务不存在！');
    }

}
