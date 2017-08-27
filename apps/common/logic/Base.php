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

namespace app\common\logic;
use think\Request;
use think\Model;
use think\Validate;

/**
 * 文档基础模型
 */
class Base extends Model
{
	public function __construct($name=''){
        if(!empty($name) && !empty($name['table_name']) && count($name) == 1){
            $this->name=$name['table_name'];
            parent::__construct();
        }else{
            parent::__construct($name);
        }
	}

	public function initialize(){
        $data = Request::instance()->param();
        $data = $data['info'];
	    if(empty($data['id']))
            unset($data['id']);
        $this->FormData = $data;
        parent::initialize();
    }

	public function updates($data = null)
	{
		//自动验证及自动完成
        /*if(!$check = $this->checkModelAttr()){
            return false;
        };*/
		$data = $this->FormData;
        $data['status'] = 99;
		$id = $this->data($data)->allowField(true)->save();


        if (!$id) {
            $this->error = '新增数据失败！';
            return false;
        }else{
            return $this->id;
        }

	}

	/**
     * 检测属性的自动验证和自动完成属性 并进行验证
     * 验证场景  insert和update二个个场景，可以分别在新增和编辑
     * @return boolean
     */
    public function checkModelAttr($model_id=false,$data=false)
    {
    	if(!$data){
            $data = $this->FormData; //获取数据
        }
        $scene = 'insert';//验证场景
        $validate_module = Validate::make([['keywords','require|date']]);
        $validate_module->scene($scene);
        if (!$validate_module->check($data)) {
        	$this->error = $validate_module->getError();
        	return false;
        }
        return true;
    }

}