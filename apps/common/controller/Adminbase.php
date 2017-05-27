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

class Adminbase extends Controller
{
	/**
	 * 后台初始化
	 */
	protected function _initialize()
    {
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
     * 获取后台分组菜单数组
     */
    final public function get_group_menu()
    {
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
    protected function lists ($model,$where=array(),$order='',$field=true)
    {
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



    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     *
     * @param string $model 模型名称,供M函数使用的参数
     * @param array  $data  修改的数据
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     */
    final protected function editRow ( $model ,$data, $where , $msg ){
        $id    = array_unique((array)input('id/a'));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>var_export(Request()->isAjax(), true)) , (array)$msg );
        if(db($model)->where($where)->update($data)!==false ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        }
    }


    /**
     * 禁用条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的 where()方法的参数
     * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     */
    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
        $data    =  array('status' => 0);
        $this->editRow( $model , $data, $where, $msg);
    }

    /**
     * 恢复条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     */
    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
        $data    =  array('status' => 1);
        $this->editRow(   $model , $data, $where, $msg);
    }

    /**
     * 还原条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     */
    protected function restore (  $model , $where = array() , $msg = array( 'success'=>'状态还原成功！', 'error'=>'状态还原失败！')){
        $data    = array('status' => 1);
        $where   = array_merge(array('status' => -1),$where);
        $this->editRow(   $model , $data, $where, $msg);
    }

    /**
     * 条目假删除
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     */
    protected function delete ( $model , $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！')) {
        $data['status']  =   -1;
        $this->editRow(   $model , $data, $where, $msg);
    }











}
