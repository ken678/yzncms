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

use app\common\model\Modelbase;
use \think\Model;

/**
 * 模型
 */
class Nodes extends Modelbase
{
    protected $name = 'collection_node';

    /**
     * 创建
     */
    public function addNode($data)
    {
        if (empty($data)) {
            throw new \Exception('数据不得为空！');
        }

        $customize_config = isset($data['customize_config']) ? $data['customize_config'] : '';
        $data = $data['data'];
        $data['customize_config'] = array();
        if (is_array($customize_config)) {
            foreach ($customize_config['name'] as $k => $v) {
                if (empty($v) || empty($customize_config['name'][$k])) {
                    continue;
                }
                $data['customize_config'][] = [
                    'title' => $customize_config['title'][$k],
                    'name' => $v,
                    'selector' => $customize_config['selector'][$k],
                    'attr' => $customize_config['attr'][$k],
                    'filter' => $customize_config['filter'][$k],
                ];

            }
        }
        $data['customize_config'] = serialize($data['customize_config']);
        $data['setting'] = serialize($data['setting']);
        self::allowField(true)->save($data);
    }

}
