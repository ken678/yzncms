<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:45:"E:\yzncms/apps/admin\view\database\index.html";i:1496719207;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1498443307;}*/ ?>
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
        <h3>数据库</h3>
        <h5>数据库恢复与备份</h5>
      </div>
      <ul class="tab-base nc-row">
            <?php $getMenu = isset($Custom)?$Custom:model('common/Menu')->getMenu(); if(is_array($getMenu) || $getMenu instanceof \think\Collection || $getMenu instanceof \think\Paginator): $i = 0; $__LIST__ = $getMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group_menu): $mod = ($i % 2 );++$i;?>
    <li><a <?php if($group_menu['action'] == \think\Request::instance()->action()): ?> class="current" <?php endif; ?> href="<?php echo url($group_menu['url'],$group_menu['parameter']); ?>" ><span><?php echo $group_menu['title']; ?></span></a></li>
<?php endforeach; endif; else: echo "" ;endif; ?>












      </ul>
  </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="网站全局基本设置，网站及其他模块相关内容在其各自栏目设置项内进行操作"></span> </div>
    <ul>
      <li>数据备份功能根据你的选择备份全部数据或指定数据，导出的数据文件可用“数据恢复”功能或 phpMyAdmin 导入</li>
      <li>建议定期备份,优化和修复数据库</li>
      <li>数据库配置修改请编辑apps/common/conf/admin/config.php</li>
    </ul>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24"  align="center"><i class="ico-check"></i></th>
          <th width="150" align="center">表名</th>
          <th width="120" align="center">数据量</th>
          <th width="120" align="center">数据大小</th>
          <th width="150" align="center">创建时间</th>
          <th width="150" align="center">说明</th>
          <th width="150" align="center">备份状态</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php if(is_array($_list) || $_list instanceof \think\Collection || $_list instanceof \think\Paginator): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$table): $mod = ($i % 2 );++$i;?>
        <tr class="hover erow" data-table="<?php echo $table['name']; ?>">
          <td><i class="ico-check"></i></td>
          <td><?php echo $table['name']; ?></td>
          <td><?php echo $table['rows']; ?></td>
          <td><?php echo format_bytes($table['data_length']); ?></td>
          <td><?php echo $table['create_time']; ?></td>
          <td><?php echo $table['comment']; ?></td>
          <td class="info">未备份</td>
          <td></td>
        </tr>
      <?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
</div>
<script>
$(function(){
$('.flex-table').flexigrid({
    height:'auto',// 高度自动
    usepager: false,// 不翻页
    striped: true,// 使用斑马线
    resizable: false,// 不调节大小
    reload: false,// 不使用刷新
    columnControl: false,// 不使用列控制
    title: '数据库列表',
    buttons : [
                 {display: '<i class="fa fa-floppy-o"></i>立即备份', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
                 {display: '<i class="fa fa fa-magic"></i>优化表', name : 'optimize', bclass : 'optimize', title : '优化表', onpress : fg_operation },
                 {display: '<i class="fa fa-wrench"></i>修复表', name : 'repair', bclass : 'repair', title : '修复表', onpress : fg_operation }
             ]
    });

    var $export = $(".add");
    function fg_operation(name, grid) {
        if (name == 'add') {
            if($('.trSelected',grid).length>0){
                $export.html('<i class="fa fa-spinner fa-spin"></i>正在发送备份请求...');
                var itemlist = [];
                $('.trSelected',grid).each(function(){
                  itemlist.push($(this).attr('data-table'));
                });
                fg_export(itemlist);
            } else {
                alert('请选择需要导出的数据库！');
                return false;
            }
        }else if (name == 'optimize' || name == 'repair') {
            if($('.trSelected',grid).length>0){
                var itemlist = [];
                $('.trSelected',grid).each(function(){
                  itemlist.push($(this).attr('data-table'));
                });
                fg_optimize(itemlist,name);
            } else {
                alert('请指定操作的的表！');
                return false;
            }
        }
    }

    function fg_export(table) {
      if (typeof table == 'number') {
          var table = new Array(table.toString());
      };
      if(!confirm('确认备份这 ' + table.length + ' 项吗？')){
          return false;
      }
      $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo url('database/export'); ?>",
            data: {tables:table},
            success: function(data){
                if (data.code){
                    tables = data.data.tables;
                    $export.html(data.msg + "开始备份，请不要关闭本页面！");
                    backup(data.data.tab);
                    window.onbeforeunload = function(){ return "正在备份数据库，请不要关闭！" }
                } else {
                    showSucc(data.msg);
                    $export.html("立即备份");
                }
            }
        });
    }

    function fg_optimize(table,name) {
      if (typeof table == 'number') {
          var table = new Array(table.toString());
      };
      if (name == 'optimize' ) {
          var url = "<?php echo url('database/optimize'); ?>";
      }else if(name == 'repair'){
          var url = "<?php echo url('database/repair'); ?>";
      }
      $.ajax({
            type: "POST",
            dataType: "json",
            url: url,
            data: {tables:table},
            success: function(data){
                if (data.code){
                  showSucc(data.msg);
                } else {
                  showSucc(data.msg);
                }
            }
        });
    }

    function backup(tab, code){
        code && showmsg(tab.id, "开始备份...(0%)");
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "<?php echo url('database/export'); ?>",
            data: tab,
            success: function(data){
                if (data.code){
                    showmsg(tab.id, data.msg);
                    if(!$.isPlainObject(data.data.tab)){
                        //$export.parent().children().removeClass("disabled");
                        $export.html('<i class="fa fa-check"></i>备份完成，点击重新备份');
                        window.onbeforeunload = function(){ return null }
                        return;
                    }
                    backup(data.data.tab, tab.id != data.data.tab.id);
                } else {
                	alert(data.msg);
                    $export.html("立即备份");
                }
            }
        });
    }

    function showmsg(id, msg){
        $("table tr[data-table="+ tables[id] +"]").children("td.info").children("div").html(msg);
    }
});
</script>
</div>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>