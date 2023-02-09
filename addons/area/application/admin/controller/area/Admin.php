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
// | 地区管理
// +----------------------------------------------------------------------
namespace app\admin\controller\area;

use app\admin\model\area\Area as AreaModel;
use app\common\controller\Adminbase;

class Admin extends Adminbase
{
    protected $modelValidate = true;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new AreaModel;
    }

    //更新地区表
    public function refresh()
    {
        $config    = get_addon_config('area');
        $amapKey   = $config['amap_webapi_key'];
        $url       = "http://restapi.amap.com/v3/config/district?key={$amapKey}&keywords=&subdistrict=3&extensions=base";
        $result    = \util\Http::get($url);
        $resultArr = json_decode($result, true);
        if (isset($resultArr['status']) && $resultArr['status'] == 1) {
            //清空数据
            $this->modelClass->where('id', '<>', 0)->delete();
            $pinyin = new \Overtrue\Pinyin\Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
            $areArr = [];
            array_multisort(array_column($resultArr['districts'][0]['districts'], 'adcode'), SORT_ASC, $resultArr['districts'][0]['districts']);
            //省
            foreach ($resultArr['districts'][0]['districts'] as $i => $province) {
                [$lng, $lat] = explode(',', $province['center']);
                $level1      = [
                    'adcode' => $province['adcode'],
                    'level'  => 1,
                    'name'   => $province['name'],
                    'pid'    => 0,
                    'pinyin' => $pinyin->permalink($province['name'], ''),
                    'first'  => $pinyin->abbr($province['name']),
                    'lng'    => $lng,
                    'lat'    => $lat,
                    'child'  => [],
                ];
                array_multisort(array_column($province['districts'], 'adcode'), SORT_ASC, $province['districts']);
                //市
                foreach ($province['districts'] as $j => $city) {
                    [$lng, $lat] = explode(',', $city['center']);
                    $level2      = [
                        'adcode' => $city['adcode'],
                        'level'  => 2,
                        'name'   => $city['name'],
                        'pinyin' => $pinyin->permalink($city['name'], ''),
                        'first'  => $pinyin->abbr($city['name']),
                        'lng'    => $lng,
                        'lat'    => $lat,
                        'child'  => [],
                    ];
                    array_multisort(array_column($city['districts'], 'adcode'), SORT_ASC, $city['districts']);
                    //区
                    foreach ($city['districts'] as $k => $area) {
                        [$lng, $lat] = explode(',', $area['center']);
                        $level3      = [
                            'adcode' => $area['adcode'],
                            'level'  => 3,
                            'name'   => $area['name'],
                            'pinyin' => $pinyin->permalink($area['name'], ''),
                            'first'  => $pinyin->abbr($area['name']),
                            'lng'    => $lng,
                            'lat'    => $lat,
                            'child'  => [],
                        ];
                        $level2['child'][$k] = $level3;
                    }
                    $level1['child'][$j] = $level2;
                }
                $areArr[$i] = $level1;
            }
            $this->areaUpdate($areArr);
        } else {
            if (isset($resultArr['infocode']) && $resultArr['infocode'] == 10001) {
                $this->error('高德地图key不正确或过期');
            } elseif (isset($resultArr['infocode']) && $resultArr['infocode']) {
                $this->error('错误码infocode：' . $resultArr['infocode']);
            } else {
                $this->error('异常错误！');
            }
        }
        $this->success("更新成功！");
    }

    protected function areaUpdate($arr, $pid = 0)
    {
        foreach ($arr as $k => $v) {
            $v['pid'] = $pid;
            $area     = AreaModel::create($v, true);
            if ($v['child']) {
                $this->areaUpdate($v['child'], $area['id']);
            }
        }
    }

}
