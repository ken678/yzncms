<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:46:"E:\yzncms/apps/admin\view\database\import.html";i:1495532883;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1491898212;}*/ ?>
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
      <span id="explanationZoom" title="网站全局基本设置，网站及其他模块相关内容在其各自栏目设置项内进行操作"></span> </div>
    <ul>
      <li>点击导入选项进行数据库恢复</li>
    </ul>
  </div>
    <table class="flex-table">
      <thead>
        <tr>
          <th width="150" align="center">操作</th>
          <th width="150" align="center">备份名</th>
          <th width="150" align="center">备份时间</th>
          <th width="150" align="center">备份大小</th>
          <th width="100" align="center">卷数</th>
          <th width="100" align="center">压缩</th>
          <th width="100" align="center">状态</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php if(is_array($_list) || $_list instanceof \think\Collection || $_list instanceof \think\Paginator): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
      <tr class="hover">
          <td class="handle">
          <span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul><li><a href="javascript:if(confirm('您确定要删除吗?')){location.href='<?php echo url('del?time='.$data['time']); ?>'};">删除</a></li>
                <li><a class="confirm-on-click" href="<?php echo url('import?time='.$data['time']); ?>">导入</a></li>
                <li><a href="<?php echo url('downFile',array('type'=>'gz','file'=>date('Ymd-His',$data['time']))); ?>">下载</a></li>
            </ul>
          </span>
          </td>
          <td><?php echo date('Ymd-His',$data['time']); ?></td>
          <td><?php echo $key; ?></td>
          <td><?php echo format_bytes($data['size']); ?></td>
          <td><?php echo $data['part']; ?></td>
          <td><?php echo $data['compress']; ?></td>
          <td class="info">-</td>
          <td></td>
      </tr>
      <?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
</div>
</div>
<script type="text/javascript">
$(function(){
  $('.flex-table').flexigrid({
    height:'auto',// 高度自动
    usepager: false,// 不翻页
    striped:false,// 不使用斑马线
    resizable: false,// 不调节大小
    title: '数据库备份列表',// 表格标题
    reload: false,// 不使用刷新
    columnControl: false,// 不使用列控制
  });
});

$('.confirm-on-click').live('click', function() {
  var self = this, status = ".";
  if(confirm('确认备份这项吗？')){
      } else {
      return false;
  }
  $.get(self.href, success, "json");
  window.onbeforeunload = function(){ return "正在还原数据库，请不要关闭！" }
  return false;

  function success(data){
      if(data.code){
          if(data.data.gz){
              data.msg += code;
              if(code.length === 5){
                  code = ".";
              } else {
                  code += ".";
              }
          }
          $(self).parents('tr').find('.info > div').text(data.msg);
          if(data.data.part){
              $.get(self.href,
                  {"part" : data.data.part, "start" : data.data.start},
                  success,
                  "json"
              );
          }  else {
              window.onbeforeunload = function(){ return null; }
          }
      } else {
          $(self).parents('tr').find('.info > div').text(data.msg);
      }
  }
});
</script>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>