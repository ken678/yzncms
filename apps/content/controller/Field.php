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
namespace app\content\controller;
use think\Db;
use think\Request;
use think\Cookie;
use app\content\model\ModelField;
use app\common\controller\Adminbase;

class Field extends Adminbase
{
	//初始化
    protected function _initialize()
    {
    	parent::_initialize();
    	$this->modelfield = new ModelField;

    }

    /**
     * 显示字段列表
     * @author 御宅男  <530765310@qq.com>
     */
	public function index()
	{
		$modelid = Request::instance()->param('modelid/d','');
        if (empty($modelid)) {
            $this->error('参数错误！');
        }
        $model = Db::name("Model")->field(true)->where(array("modelid" => $modelid))->find();
        if (empty($model)) {
            $this->error('该模型不存在！');
        }
        // 记录当前列表页的cookie
        Cookie::set('__forward__',       $_SERVER['REQUEST_URI']);

        //根据模型读取字段列表
        $data = $this->modelfield->getModelField($modelid);
        //禁止被禁用的字段列表
        $this->assign("forbid_fields", $this->modelfield->forbid_fields);
        //禁止被删除的字段列表
        $this->assign("forbid_delete", $this->modelfield->forbid_delete);

        $this->assign("modelid", $modelid);
        $this->assign("data", $data);
        return $this->fetch();
	}

    /**
     * 增加字段
     * @author 御宅男  <530765310@qq.com>
     */
    public function add()
    {
        $modelid = Request::instance()->param('modelid/d','');
        if (empty($modelid)) {
            $this->error('参数错误！');
        }
        if(Request::instance()->isPost()){
            //增加字段
            $res = $this->modelfield->addField();
            if(!$res){
                $this->error($this->modelfield->getError());
            }else{
                $this->success('新增成功', Cookie::get('__forward__'));
            }
        }else{
            //获取并过滤可用字段类型
            foreach ($this->modelfield->getFieldTypeList() as $formtype => $name) {
                if (!$this->modelfield->isAddField($formtype, $formtype, $modelid)) {
                    continue;
                }
                $all_field[$formtype] = $name;
            }

            $this->assign("modelid", $modelid);
            $this->assign("all_field", $all_field);
            return $this->fetch();
        }

    }

    /**
     * 删除字段
     * @author 御宅男  <530765310@qq.com>
     */
    public function delete()
    {
        //字段ID
        $fieldid = Request::instance()->param('fieldid/d','');
        if (empty($fieldid)) {
            $this->error('字段ID不能为空！');
        }
        if ($this->modelfield->deleteField($fieldid)) {
            $this->success("字段删除成功！");
        } else {
            $error = $this->modelfield->getError();
            $this->error($error ? $error : "删除字段失败！");
        }
    }










}
