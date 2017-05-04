<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:47:"E:\yzncms/apps/admin\view\action\actionlog.html";i:1493874075;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1493872994;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1491898212;}*/ ?>
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
            <?php if(is_array($__GROUP_MENU__) || $__GROUP_MENU__ instanceof \think\Collection || $__GROUP_MENU__ instanceof \think\Paginator): $i = 0; $__LIST__ = $__GROUP_MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group_menu): $mod = ($i % 2 );++$i;?>
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
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>操作人</dt>
            <dd>
             <input type="text" value="" name="admin_name" class="s-input-txt">
            </dd>
          </dl>
          <dl>
            <dt>操作内容</dt>
            <dd>
             <input type="text" value="" name="content" class="s-input-txt">
            </dd>
          </dl>
          <dl>
            <dt>IP</dt>
            <dd>
             <input type="text" value="" name="ip" class="s-input-txt">
            </dd>
          </dl>
          <dl>
            <dt>操作时间</dt>
            <dd>
              <label>
                <input readonly id="query_start_date" placeholder="请选择起始时间" name=query_start_date value="" type="text" class="s-input-txt" />
              </label>
              <label>
                <input readonly id="query_end_date" placeholder="请选择结束时间" name="query_end_date" value="" type="text" class="s-input-txt" />
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i>撤销</a></div>
    </form>
  </div>
</div>

<script type="text/javascript">
$(function(){
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions(<?php echo url("","",true,false);?>).flexReload();
    });
    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions(<?php echo url("","",true,false);?>).flexReload();
        $("#formSearch")[0].reset();
    });
    $("#flexigrid").flexigrid({
      url: "<?php echo url('action/get_xml'); ?>",
      colModel : [
          {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
          {display: '操作人', name : 'admin_name', width : 120, sortable : true, align: 'left'},
          {display: '行为', name : 'content', width : 500, sortable : false, align : 'left'},
          {display: '时间', name : 'id', width : 140, sortable : true, align: 'center'},
          {display: 'IP', name : 'ip', width : 120, sortable : true, align: 'left'}
          ],
      buttons : [
          {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
          {display: '<i class="fa fa-trash"></i>删除6个月前的数据', name : 'delete_ago', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
          {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出excel文件,如果不选中行，将导出列表所有数据', onpress : fg_operate }
      ],
      searchitems : [
          {display: '操作人', name : 'admin_name'},
          {display: '操作内容', name : 'content'}
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
              url: "index.php?act=admin_log&op=list_del",
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
        url: "index.php?act=admin_log&op=list_del",
        data: "del_id="+id,
        success: function(data){
            if (data.state){
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