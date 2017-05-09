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
namespace app\common\controller;
use think\Controller;
use app\admin\model\AuthRule;
use app\admin\model\AuthGroup;

class Adminbase extends Controller {
	/**
	 * 后台初始化
	 */
	protected function _initialize(){
        define('UID',is_login());
        //过滤不需要登陆的行为
        $allowUrl = ['admin/index/login',
                     'admin/index/logout',
                     'admin/index/getverify'
                    ];
        $request = request();
        $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
        if(in_array($visit,$allowUrl)){

        }else{
            if(!UID){
                $this->error('请先登陆',url('admin/index/login'));
            }else{
                /* 读取数据库中的配置 */
                $config = cache('DB_CONFIG_DATA');
                if(!$config){
                    $config =   api('Config/lists');
                    cache('DB_CONFIG_DATA', $config);
                }
                config($config);//添加配置
                define('IS_ROOT',   is_administrator());
            }
        }
	}


	/**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    final protected function checkRule($rule, $type=AuthRule::rule_url, $mode='url'){
        static $Auth    =   null;
        if (!$Auth) {
            $Auth       =   new \com\Auth();
        }
        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
    }


    /**
     * 获取后台分组菜单数组
     */
    final public function get_group_menu(){
        $getMenu = isset($Custom)?$Custom:model('Admin/Menu')->getMenu();
        return $getMenu;
    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$field=true){
        $options    =   array();
        $REQUEST    =   (array)input('request.');
        if(is_string($model)){
            $model  =   db($model);
        }
        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['sortorder']) && isset($REQUEST['sortname']) && in_array(strtolower($REQUEST['sortorder']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['sortname'].'` '.$REQUEST['sortorder'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['sortorder'],$REQUEST['sortname']);

        if( empty($options['where'])){
            unset($options['where']);
        }
        if( isset($REQUEST['rp']) ){
            $listRows = (int)$REQUEST['rp'];
        }else{
            $listRows = config('list_rows') > 0 ? config('list_rows') : 10;
        }
        if( isset($REQUEST['curpage']) ){
            $curpage= (int)$REQUEST['curpage'];
        }else{
            $curpage= 1;
        }
        if( !empty($where)){
            $options['where']   =   $where;
            $total        =   $model->where($options['where'])->count();
            $list = $model->where($options['where'])->order($options['order'])->field($field)->paginate($listRows,false,['page'=> $curpage]);
        }else{
            $total        =   $model->count();
            $list = $model->order($options['order'])->field($field)->paginate($listRows,false,['page'=> $curpage]);
        }
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('_page', $page);
        $this->assign('_total',$total);
        if($list && !is_array($list)){
            $list=$list->toArray();
        }
        return $list;
    }









}
