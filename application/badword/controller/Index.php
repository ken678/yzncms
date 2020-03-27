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
// | 敏感词过滤
// +----------------------------------------------------------------------
namespace app\badword\controller;

use app\badword\model\Badword as Badword_Model;
use app\common\controller\Adminbase;

class Index extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 首页
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 10);
            $page = $this->request->param('page/d', 1);
            $_list = Badword_Model::order('id', 'desc')->page($page, $limit)->select();
            $total = Badword_Model::count();
            $result = array("code" => 0, "count" => $total, "data" => $_list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (Badword_Model::create($data)) {
                $this->success("添加成功！", url("index"));
            } else {
                $this->error("添加失败！");
            }
        } else {
            return $this->fetch();
        }

    }

    /**
     * 新增
     */
    public function edit()
    {
        $id = $this->request->param('id/d', 0);
        empty($id) && $this->error('参数错误！');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (Badword_Model::where('id', $id)->update($data)) {
                $this->success("编辑成功！", url("index"));
            } else {
                $this->error("编辑失败！");
            }
        } else {
            $info = Badword_Model::get($id);
            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    //导入
    public function import()
    {
        if ($this->request->isPost()) {
            $files = $this->request->file('file');
            if ($files == null) {
                $this->error("请选择上传文件！");
            }
            $file_name = $files->getInfo('tmp_name');
            if (strtolower(substr($files->getInfo('name'), -3, 3)) != 'txt') {
                $this->error("只支持上传TXT的格式！");
            }
            //读取文件
            $data = file_get_contents($file_name);
            $arr = array_unique(explode(PHP_EOL, $data));
            if (is_array($arr) || empty($arr)) {
                foreach ($arr as $key => $value) {
                    $sql_str = [];
                    $attr = parse_attr($value);
                    $sql_str['badword'] = $attr[0];
                    $sql_str['replaceword'] = empty($attr[1]) ? '' : $attr[1];
                    if (empty($sql_str['badword'])) {
                        continue;
                    } else {
                        $check_badword = Badword_Model::where(['badword' => $sql_str['badword']])->find();
                        if ($check_badword) {
                            continue;
                        }
                        Badword_Model::create($sql_str);
                    }
                    unset($sql_str, $check_badword);
                }
            }
            $this->success("批量导入成功！");

        }
    }

    /**
     * 删除
     */
    public function del($ids)
    {
        empty($ids) && $this->error('参数错误！');
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        if (false == Badword_Model::where('id', 'in', $ids)->delete()) {
            $this->error('删除失败！');
        } else {
            $this->success('删除成功！');
        }
    }

}
