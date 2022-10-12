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
// | 会员附件类
// +----------------------------------------------------------------------
namespace app\member\controller;

use app\attachment\model\Attachment;

class Attachments extends MemberBase
{

    //附件列表页
    public function select()
    {
        $this->request->filter('trim,strip_tags');
        if ($this->request->isAjax()) {
            $page    = $this->request->param('page', 1);
            $limit   = $this->request->param('limit', 15);
            $filters = $this->request->param('filter', '{}');
            $ops     = $this->request->param('op', '{}');
            $filters = (array) json_decode($filters, true);
            $ops     = (array) json_decode($ops, true);
            $where   = [];
            foreach ($filters as $key => $val) {
                $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';
                switch (strtolower($op)) {
                    case '=':
                        $where[] = [$key, '=', $val];
                        break;
                    case '%*%':
                        $where[] = [$key, 'LIKE', "%{$val}%"];
                        break;
                    case '*%':
                        $where[] = [$key, 'LIKE', "{$val}%"];
                        break;
                    case '%*':
                        $where[] = [$key, 'LIKE', "%{$val}"];
                        break;
                    case 'range':
                        list($beginTime, $endTime) = explode(' - ', $val);
                        $where[]                   = [$key, '>=', strtotime($beginTime)];
                        $where[]                   = [$key, '<=', strtotime($endTime)];
                        break;
                    default:
                        $where[] = [$key, $op, "%{$val}"];
                };
            };

            $total = Attachment::where($where)
                ->where($where)
                ->where('uid', $this->auth->id)
                ->order('id', 'desc')
                ->count();
            $_list = Attachment::where($where)
                ->where($where)
                ->where('uid', $this->auth->id)
                ->page($page, $limit)
                ->order('id', 'desc')
                ->select();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch('/attachment');
    }
}
