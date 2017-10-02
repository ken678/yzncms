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
namespace app\Api\controller;

use app\common\controller\Base;
use think\Db;

/**
 * 点击数
 */
class Hits extends Base
{
    //内容模型
    protected $db;
    //获取点击数
    public function index()
    {
        //栏目ID
        $catid = $this->request->param('catid/d', 0);
        //信息ID
        $id = $this->request->param('id/d', 0);
        //模型ID
        $modelid = (int) getCategory($catid, 'modelid');
        if (empty($modelid)) {
            exit;
        }
        $hitsid = 'c-' . $modelid . '-' . $id;
        $r = $this->get_count($hitsid);
        if (!$r) {
            exit;
        }
        $r['modelid'] = $modelid;
        //增加点击率
        $this->hits($hitsid);
        echo json_encode($r);
    }

    /**
     * 获取点击数量
     * @param $hitsid
     */
    private function get_count($hitsid)
    {
        $r = Db::name('hits')->where(array('hitsid' => $hitsid))->find();
        if (!$r) {
            return false;
        }

        return $r;
    }

    /**
     * 增加点击数
     * @param type $r 点击相关数据
     * @return boolean
     */
    private function hits($hitsid)
    {
        $r = Db::name('hits')->where(array('hitsid' => $hitsid))->find();
        if (empty($r)) {
            return false;
        }
        //当前时间
        $time = time();
        //总点击+1
        $views = $r['views'] + 1;
        //昨日
        $yesterdayviews = (date('Ymd', $r['updatetime']) == date('Ymd', strtotime('-1 day'))) ? $r['dayviews'] : $r['yesterdayviews'];
        //今日点击
        $dayviews = (date('Ymd', $r['updatetime']) == date('Ymd', $time)) ? ($r['dayviews'] + 1) : 1;
        //本周点击
        $weekviews = (date('YW', $r['updatetime']) == date('YW', $time)) ? ($r['weekviews'] + 1) : 1;
        //本月点击
        $monthviews = (date('Ym', $r['updatetime']) == date('Ym', $time)) ? ($r['monthviews'] + 1) : 1;
        $data = array(
            'views' => $views,
            'yesterdayviews' => $yesterdayviews,
            'dayviews' => $dayviews,
            'weekviews' => $weekviews,
            'monthviews' => $monthviews,
            'updatetime' => $time,
        );
        $status = Db::name('hits')->where(array('hitsid' => $hitsid))->update($data);
        return false !== $status ? true : false;
    }

}
