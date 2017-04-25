<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:43:"E:\yzncms/apps/admin\view\manager\init.html";i:1493024324;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1492155695;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1491898212;}*/ ?>
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

<script type="text/javascript" src="__STATIC__/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="__STATIC__/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/admin.js"></script>
<script type="text/javascript" src="http://v5.33hao.com/admin/resource/js/flexigrid.js"></script>


</head>
<body style="background-color: #FFF; overflow: auto;">
<script type="text/javascript" src="__STATIC__/admin/js/jquery.picTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>管理员管理</h3>
        <h5>网站后台管理员索引及管理</h5>
      </div>
      <ul class="tab-base nc-row">
            <?php if(is_array($__GROUP_MENU__) || $__GROUP_MENU__ instanceof \think\Collection || $__GROUP_MENU__ instanceof \think\Paginator): $i = 0; $__LIST__ = $__GROUP_MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group_menu): $mod = ($i % 2 );++$i;?>
    <li><a <?php if($group_menu['action'] == \think\Request::instance()->action()): ?> class="current" <?php endif; ?> href="<?php echo url($group_menu['url'],$group_menu['parameter']); ?>" ><span><?php echo $group_menu['title']; ?></span></a></li>
<?php endforeach; endif; else: echo "" ;endif; ?>

      </ul>
  </div>
  </div>
  <table class="flex-table">
      <thead>
        <tr>
          <th width="24"  align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle">操作</th>
          <th width="100" align="center">登录名</th>
          <th width="120" align="center">所属角色</th>
          <th width="120" align="center">最后登录IP</th>
          <th width="120" align="center">最后登录时间</th>
          <th width="120"  align="center">E-mail</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php if(is_array($_list) || $_list instanceof \think\Collection || $_list instanceof \think\Paginator): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr class="hover erow">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a class="btn red" href="index.php?act=admin&amp;op=admin_del&amp;admin_id=2" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
          <a class="btn blue" href="index.php?act=admin&amp;op=admin_edit&amp;admin_id=2"><i class="fa fa-pencil-square-o"></i>编辑</a></td>
          <td><?php echo $vo['username']; ?></td>
          <td>编辑</td>
          <td><?php echo long2ip($vo['last_login_ip']); ?></td>
          <td><?php echo time_format($vo['last_login_time']); ?></td>
          <td><?php echo $vo['email']; ?></td>
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
  title: '管理员列表',
  buttons : [
               {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
           ]
  });

function fg_operation(name, grid) {
    if (name == 'add') {
        window.location.href = "<?php echo url('admin/management/adminadd'); ?>";
    }
}
</script>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>