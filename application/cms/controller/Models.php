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
// | 模型管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\cms\model\Models as Models_Model;
use app\common\controller\Adminbase;
use think\Db;

class Models extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Models = new Models_Model;
    }

    /**
     * 模型列表
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = $this->Models->select();
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 添加模型
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $result = $this->validate($data, 'Models');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Models->addModel($data)) {
                $this->success("添加模型成功！", url('models/index'));
            } else {
                $error = $this->Models->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            return $this->fetch();
        }
    }

}
