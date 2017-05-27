<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"E:\yzncms/apps/admin\view\auth_manager\managergroup.html";i:1495877644;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;}*/ ?>
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
    <div class="item-title"><a class="back" href="index.php?act=admin&op=gadmin" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>权限设置 - 编辑权限组“编辑”</h3>
        <h5>管理中心操作权限及分组设置</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
      <ul>
        <li>可在标题处全选所有功能或根据功能模块逐一选择操作权限，提交保存后生效。</li>
      </ul>
  </div>
  <form id="add_form" method="post" name="adminForm" style="margin-bottom: 50px;">
    <div class="ncap-form-all">
      <div class="title">
        <h3>权限操作设置详情</h3>
      </div>
      <?php if(is_array($node_list) || $node_list instanceof \think\Collection || $node_list instanceof \think\Paginator): $i = 0; $__LIST__ = $node_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$node): $mod = ($i % 2 );++$i;?>
      <dl class="row">
          <dt class="tit">
               <span><input class="checkbox" type="checkbox" nctype="modulesAll"><?php echo $node['title']; ?>模块功能</span>
          </dt>
          <dd class="opt nobg nopd nobd nobs">
          <?php if(isset($node['child'])): if(is_array($node['child']) || $node['child'] instanceof \think\Collection || $node['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $node['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?>
              <div class="ncap-account-container">
                  <h4><input class="checkbox" type="checkbox" nctype="groupAll"><?php echo $child['title']; ?>操作</h4>
                  <ul class="ncap-account-container-list">
                  <?php if(is_array($child['operator']) || $child['operator'] instanceof \think\Collection || $child['operator'] instanceof \think\Paginator): $i = 0; $__LIST__ = $child['operator'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$op): $mod = ($i % 2 );++$i;?>
                  <li><input class="checkbox" type="checkbox" value="<?php echo $auth_rules[$op['url']] ?>" rules[]><?php echo $op['title']; ?></li>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                  </ul>
              </div>
          <?php endforeach; endif; else: echo "" ;endif; endif; ?>
          </dd>
      </dl>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
  </form>
</div>
<script type="text/javascript" charset="utf-8">
    +function($){
        // 全选
        $('#limitAll').click(function(){
          $('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
        });
        // 功能模块
        $('input[nctype="modulesAll"]').click(function(){
            $(this).parents('dt:first').next().find('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
        });
        // 功能组
        $('input[nctype="groupAll"]').click(function(){
            $(this).parents('h4:first').next().find('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
        });



        var rules = [<?php echo $this_group['rules']; ?>];
        $('.checkbox').each(function(){
            if( $.inArray( parseInt(this.value,10),rules )>-1 ){
                $(this).prop('checked',true);
            }
            if(this.value==''){
                $(this).closest('span').remove();
            }
        });
    }(jQuery);
</script>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>