<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 会员签到管理
// +----------------------------------------------------------------------
namespace addons\signin\Controller;

use addons\signin\model\Signin as SigninModel;
use app\member\controller\MemberBase;
use app\member\service\User;
use think\Db;
use util\Date;

class Index extends MemberBase
{
    // 签到首页
    public function index()
    {
        $config   = get_addon_config('signin');
        $signdata = $config['signinscore'];
        $date     = $this->request->request('date', date("Y-m-d"), "trim");
        $time     = strtotime($date);

        $lastdata    = SigninModel::where('uid', User::instance()->isLogin())->order('create_time', 'desc')->find();
        $successions = $lastdata && $lastdata['create_time'] > Date::unixtime('day', -1) ? $lastdata['successions'] : 0;
        $signin      = SigninModel::where('uid', User::instance()->isLogin())->whereTime('create_time', 'today')->find();

        $calendar = new \addons\signin\library\Calendar();
        $list     = SigninModel::where('uid', User::instance()->isLogin())
            ->field('id,create_time')
            ->whereTime('create_time', 'between', [date("Y-m-1", $time), date("Y-m-1", strtotime("+1 month", $time))])
            ->select();
        foreach ($list as $index => $item) {
            $calendar->addEvent(date("Y-m-d", $item->create_time), date("Y-m-d", $item->create_time), "", false, "signed");
        }
        $this->assign('fillupscore', $config['fillupscore']);
        //$this->assignconfig('isfillup', $config['isfillup']);
        $this->assign('calendar', $calendar);
        $this->assign('date', $date);
        $this->assign('successions', $successions);
        $successions++;
        $score = isset($signdata['s' . $successions]) ? $signdata['s' . $successions] : $signdata['sn'];
        $this->assign('signin', $signin);
        $this->assign('score', $score);
        $this->assign('config', $config);
        return $this->fetch();
    }

    // 立即签到
    public function dosign()
    {
        if ($this->request->isPost()) {
            $config   = get_addon_config('signin');
            $signdata = $config['signinscore'];

            $lastdata    = SigninModel::where('uid', User::instance()->isLogin())->order('create_time', 'desc')->find();
            $successions = $lastdata && $lastdata['create_time'] > Date::unixtime('day', -1) ? $lastdata['successions'] : 0;
            $signin      = SigninModel::where('uid', User::instance()->isLogin())->whereTime('create_time', 'today')->find();
            if ($signin) {
                $this->error('今天已签到,请明天再来!');
            } else {
                $successions++;
                $score = isset($signdata['s' . $successions]) ? $signdata['s' . $successions] : $signdata['sn'];
                try {
                    SigninModel::create(['uid' => User::instance()->isLogin(), 'successions' => $successions, 'create_time' => time()]);
                    //\app\common\model\User::score($score, User::instance()->isLogin(), "连续签到{$successions}天");
                    //增加积分
                    \think\Db::name('member')->where(['id' => User::instance()->isLogin()])->setInc('point', $score);
                } catch (Exception $e) {
                    $this->error('签到失败,请稍后重试');
                }
                $this->success('签到成功!连续签到' . $successions . '天!获得' . $score . '积分');
            }
        }
        $this->error("请求错误");
    }

    // 签到补签
    public function fillup()
    {
        $date   = $this->request->request('date');
        $time   = strtotime($date);
        $config = get_addon_config('signin');
        if (!$config['isfillup']) {
            $this->error('暂未开启签到补签');
        }
        if ($time > time()) {
            $this->error('无法补签未来的日期');
        }
        if ($config['fillupscore'] > User::instance()->point) {
            $this->error('你当前积分不足');
        }
        $days = Date::span(time(), $time, 'days');
        if ($config['fillupdays'] < $days) {
            $this->error("只允许补签{$config['fillupdays']}天的签到");
        }
        $count = SigninModel::where('uid', User::instance()->isLogin())
            ->where('type', 'fillup')
            ->whereTime('create_time', 'between', [Date::unixtime('month'), Date::unixtime('month', 0, 'end')])
            ->count();
        if ($config['fillupnumsinmonth'] <= $count) {
            $this->error("每月只允许补签{$config['fillupnumsinmonth']}次");
        }
        Db::name('signin')->whereTime('create_time', 'd')->select();
        $signin = SigninModel::where('uid', User::instance()->isLogin())
            ->where('type', 'fillup')
            ->whereTime('create_time', 'between', [$date, date("Y-m-d 23:59:59", $time)])
            ->count();
        if ($signin) {
            $this->error("该日期无需补签到");
        }
        $successions = 1;
        $prev        = $signin        = SigninModel::where('uid', User::instance()->isLogin())
            ->whereTime('create_time', 'between', [date("Y-m-d", strtotime("-1 day", $time)), date("Y-m-d 23:59:59", strtotime("-1 day", $time))])
            ->find();
        if ($prev) {
            $successions = $prev['successions'] + 1;
        }
        try {
            //\app\common\model\User::score(-$config['fillupscore'], User::instance()->isLogin(), '签到补签');
            //寻找日期之后的
            $nextList = SigninModel::where('uid', User::instance()->isLogin())
                ->where('create_time', '>=', strtotime("+1 day", $time))
                ->order('create_time', 'asc')
                ->select();
            foreach ($nextList as $index => $item) {
                //如果是阶段数据，则中止
                if ($index > 0 && $item->successions == 1) {
                    break;
                }
                $day = $index + 1;
                if (date("Y-m-d", $item->create_time) == date("Y-m-d", strtotime("+{$day} day", $time))) {
                    $item->successions = $successions + $day;
                    $item->save();
                }
            }
            SigninModel::create(['uid' => User::instance()->isLogin(), 'type' => 'fillup', 'successions' => $successions, 'create_time' => $time + 43200]);
        } catch (Exception $e) {
            $this->error('补签失败,请稍后重试');
        }
        $this->success('补签成功');
    }

    // 排行榜
    public function rank()
    {
        $data = SigninModel::with(["user"])
            ->where("create_time", ">", Date::unixtime('day', -1))
            ->field("uid,MAX(successions) AS days")
            ->group("uid")
            ->order("days", "desc")
            ->limit(10)
            ->select();
        foreach ($data as $index => $datum) {
            $datum->getRelation('user')->visible(['id', 'username', 'nickname', 'avatar']);
        }
        $this->success("", "", ['ranklist' => collection($data)->toArray()]);
    }
}
