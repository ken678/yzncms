<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"E:\yzncms/apps/admin\view\auth_manager\index.html";i:1496288932;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="__STATIC__/admin/css/index.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/css/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="__STATIC__/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript">
var SITEURL = '';
</script>
<script type="text/javascript" src="__STATIC__/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="__STATIC__/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/admin.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="__STATIC__/admin/js/flexigrid.js"></script>

<script type="text/javascript" src="__STATIC__/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/jquery.mousewheel.js"></script>
</head>
<body style="background-color: #FFF; overflow: auto;">
<script type="text/javascript" src="__STATIC__/admin/js/jquery.picTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>权限设置</h3>
        <h5>管理中心操作权限及分组设置</h5>
      </div>
    </div>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24"  align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle">操作</th>
          <th width="150" align="center">权限组</th>
          <th width="250" align="center">描述</th>
          <th width="150" align="center">状态</th>
          <th width="300" align="center">授权</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php if(is_array($_list) || $_list instanceof \think\Collection || $_list instanceof \think\Paginator): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr class="hover">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
          <span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul>
                <li><a href="<?php echo url('AuthManager/editgroup?id='.$vo['id']); ?>">编辑</a></li>
                <?php if($vo['status'] == '1'): ?>
                <li><a onclick="fg_editRow(<?php echo $vo['id']; ?>,'forbidGroup')" href="javascript:;">禁用</a></li>
                <?php else: ?>
                <li><a onclick="fg_editRow(<?php echo $vo['id']; ?>,'resumeGroup')" href="javascript:;">启用</a></li>
                <?php endif; ?>
                <li><a onclick="fg_editRow(<?php echo $vo['id']; ?>,'deleteGroup')" href="javascript:;">删除</a></li>
            </ul>
          </span>
          </td>
          <td><?php echo $vo['title']; ?></td>
          <td><?php echo mb_strimwidth($vo['description'],0,60,"...","utf-8"); ?></td>
          <td>
          <?php if($vo['status'] == '1'): ?>
          <span class="on"><i class="fa fa-toggle-on"></i><?php echo $vo['status_text']; ?></span>
          <?php else: ?>
          <span class="off"><i class="fa fa-toggle-off"></i><?php echo $vo['status_text']; ?></span>
          <?php endif; ?>
          </td>
          <td>
              <a class="btn blue" href="<?php echo url('AuthManager/access?group_name='.$vo['title'].'&group_id='.$vo['id']); ?>"><i class="fa fa-eye"></i>访问授权</a>
              <a class="btn blue" href=""><i class="fa fa-sitemap"></i>分类授权</a>
              <a class="btn blue" href=""><i class="fa fa-user"></i>成员授权</a>
          </td>
          <td></td>
        </tr>
      <?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
</div>
<script>
$('.flex-table').flexigrid({
	height:'auto',// 高度自动
	usepager: false,// 不翻页
	striped: true,// 使用斑马线
	resizable: false,// 不调节大小
	reload: false,// 不使用刷新
	columnControl: false,// 不使用列控制
	title: '权限组列表',
	buttons : [
               {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
           ]
	});

function fg_operation(name, grid) {
    if (name == 'add') {
        window.location.href = 'index.php?act=admin&op=gadmin_add';
    }
}

function fg_editRow(id,methods) {
  if (typeof id == 'number') {
      var id = new Array(id.toString());
  };
  id = id.join(',');
  switch(methods){
  case 'resumegroup':
  var url = "<?php echo url('AuthManager/changeStatus?method=resumegroup'); ?>";
  break;
  case 'forbidGroup':
  var url = "<?php echo url('AuthManager/changeStatus?method=forbidGroup'); ?>";
  break;
  case 'resumeGroup':
  var url = "<?php echo url('AuthManager/changeStatus?method=resumegroup'); ?>";
  break;
  case 'deleteGroup':
  if(!confirm('删除后将不能恢复，确认删除吗？')){
    return false;
  }
  var url = "<?php echo url('AuthManager/changeStatus?method=deleteGroup'); ?>";
  break;
  }
  $.ajax({
        type: "GET",
        dataType: "json",
        url: url,
        data: {id:id},
        success: function(data){
            if (data.code){
                window.location.reload();
            } else {
                alert(data.msg);
            }
        }
    });
}
</script>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>