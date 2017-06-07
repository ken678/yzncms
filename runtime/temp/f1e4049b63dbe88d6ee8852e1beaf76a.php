<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:47:"E:\yzncms/apps/admin\view\action\actionlog.html";i:1494837939;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1496823711;}*/ ?>
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
        <h3>操作日志</h3>
        <h5>管理中心管理操作日志内容</h5>
      </div>
      <ul class="tab-base nc-row">
            <?php $getMenu = isset($Custom)?$Custom:model('Admin/Menu')->getMenu(); if(is_array($getMenu) || $getMenu instanceof \think\Collection || $getMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $getMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group_menu): $mod = ($i % 2 );++$i;?>
    <li><a <?php if($group_menu['action'] == \think\Request::instance()->action()): ?> class="current" <?php endif; ?> href="<?php echo url($group_menu['url'],$group_menu['parameter']); ?>" ><span><?php echo $group_menu['title']; ?></span></a></li>
<?php endforeach; endif; else: echo "" ;endif; ?>












      </ul>
  </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span>
    </div>
    <ul>
      <li>系统默认关闭了操作日志，如需开启，请编辑admin/config/config.ini.php: $config['sys_log'] = true;</li>
      <li>开启操作日志可以记录管理人员的关键操作，但会轻微加重系统负担</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
      url: "<?php echo url('action/get_xml'); ?>",
      colModel : [
          {display: '操作', name : 'id', width : 60, sortable : false, align: 'center', className: 'handle-s'},
          {display: '操作人', name : 'user_id', width : 120, sortable : true, align: 'left'},
          {display: '行为', name : 'remark', width : 500, sortable : false, align : 'left'},
          {display: '时间', name : 'create_time', width : 140, sortable : true, align: 'center'},
          {display: 'IP', name : 'action_ip', width : 120, sortable : true, align: 'left'}
          ],
      buttons : [
          {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
          {display: '<i class="fa fa-trash"></i>删除1个月前的数据', name : 'delete_ago', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
          {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出excel文件,如果不选中行，将导出列表所有数据', onpress : fg_operate }
      ],
      searchitems : [
          {display: '操作人', name : 'action_id'},
          {display: '操作内容', name : 'remark'}
      ],
      sortname: "id",
      sortorder: "desc",
      title: '管理员操作日志列表'
    })
});

function fg_operate(name, grid) {
    if (name == 'csv') {
      var itemlist = new Array();
        if($('.trSelected',grid).length>0){
            $('.trSelected',grid).each(function(){
              itemlist.push($(this).attr('data-id'));
            });
        }
        fg_csv(itemlist);
    }else if (name == 'delete') {
        if($('.trSelected',grid).length>0){
            var itemlist = new Array();
            $('.trSelected',grid).each(function(){
              itemlist.push($(this).attr('data-id'));
            });
            fg_delete(itemlist);
        } else {
            return false;
        }
    }else if (name == 'delete_ago') {
      if(confirm('删除后将不能恢复，确认删除吗？')){
        $.ajax({
              type: "GET",
              dataType: "json",
              url: "<?php echo url('action/remove'); ?>",
              data: "type=ago",
              success: function(data){
                  if (data.state){
                      $("#flexigrid").flexReload();
                  } else {
                    alert(data.msg);
                  }
              }
          });
        }
    }
}
function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_step1&id=' + id;
}
function fg_delete(id) {
  if (typeof id == 'number') {
      var id = new Array(id.toString());
  };
  if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
    id = id.join(',');
  } else {
        return false;
    }
  $.ajax({
        type: "GET",
        dataType: "json",
        url: "<?php echo url('action/remove'); ?>",
        data: "ids="+id,
        success: function(data){
            if (data.code){
                $("#flexigrid").flexReload();
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