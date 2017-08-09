$(document).ready(function () {
    //鼠标移动tips提示
    function itip(msg, selector, ipoint, ifixed){ipoint=ipoint==undefined?1:ipoint;layer.tips(msg,selector,{tips:[ipoint,'#333'],fixed:ifixed,time:6000});}
    $(".itip").each(function(i,n){
        $(n).hover(function(){itip($(n).attr("alt"),$(n),undefined,$(n).hasClass("ifixed"));},function(){layer.closeAll('tips');});
    });

    // 侧边导航二级菜单切换（展开式）
    $('.nav-tabs').each(function(){
        $(this).find('dl > dt > a').each(function(i){
            $(this).parent().next().css('top', (-70)*i + 'px');
            $(this).click(function(){
                if ($('.admincp-container').hasClass('fold')) {
                    return;
                }
                $('.sub-menu').hide();
                $('.nav-tabs').find('dl').removeClass('active');
                $(this).parents('dl:first').addClass('active');
                $(this).parent().next().show().find('a:first').click();
            });
        });
    });

    // 侧边导航展示形式切换(收缩侧边栏)
    $('#foldSidebar > i').click(function(){
        if ($('.admincp-container').hasClass('unfold')) {
            $(this).addClass('fa-indent').removeClass('fa-outdent');
            $('.sub-menu').removeAttr('style');
            $('.admincp-container').addClass('fold').removeClass('unfold');
        } else {
            $(this).addClass('fa-outdent').removeClass('fa-indent');
            $('.nav-tabs').each(function(i){
                $(this).find('dl').each(function(i){
                    $(this).find('dd').css('top', (-70)*i + 'px');
                    if ($(this).hasClass('active')) {
                        $(this).find('dd').show();
                    }
                });
            });
            $('.admincp-container').addClass('unfold').removeClass('fold');
        }
    });

     // 侧边导航三级级菜单点击
    $('.sub-menu').find('a').click(function(){
        openItem($(this).attr('data-param'));
    });

     // 顶部各个模块切换
    $('.nc-module-menu').find('a').click(function(){
        if ($('.admincp-container').hasClass('unfold')) {
            $('.sub-menu').hide();
        }
        $('.nc-module-menu').find('li').removeClass('active');
        _modules = $(this).parent().addClass('active').attr('data-param');
        $('div[id^="admincpNavTabs_"]').hide();
        $('#admincpNavTabs_' + _modules).show().find('dl').removeClass('active').first().addClass('active').find('dd').find('li > a:first').click();
    });

    // 导航菜单切换
    $('a[data-param^="map-"]').click(function(){
        $(this).parent().addClass('selected').siblings().removeClass('selected');
        $('div[data-param^="map-"]').hide();
        $('div[data-param="' + $(this).attr('data-param') + '"]').show();
    });
    $('div[data-param^="map-"]').find('i').click(function(){
        var $this = $(this);
        var _menuid = $this.prev().attr('data-menuid');
        var _param = $this.prev().attr('data-param');
        if ($this.parent().hasClass('selected')) {
            $.getJSON(COMMON_OPERATIONS_URL, {type : 'del', menuid : _menuid}, function(data){
                if (data) {
                    $this.parent().removeClass('selected');
                    $('ul[nctype="quick_link"]').find('a[onclick="openItem(\'' + _param + '\')"]').parent().remove();
                }
            });
        } else {
            var _name = $this.prev().html();
            $.getJSON(COMMON_OPERATIONS_URL, {type : 'add', menuid : _menuid}, function(data){
                if (data) {
                    $this.parent().addClass('selected');
                    $('ul[nctype="quick_link"]').append('<li><a onclick="openItem(\'' + _param + '\')" href="javascript:void(0);">' + _name + '</a></li>');
                }
            });
        }
    }).end().find('a').click(function(){
        openItem($(this).attr('data-param'));
    });

    if ($.cookie('workspaceParam') == null) {
        // 默认选择第一个菜单
        $('.nc-module-menu').find('li:first > a').click();
    } else {
        openItem($.cookie('workspaceParam'));
    }
    // 导航菜单  显示
    $('a[nctype="map_on"],a[class="add-menu"]').click(function(){
        $('div[nctype="map_nav"]').show();
    });
    // 导航菜单 隐藏
    $('a[nctype="map_off"]').click(function(){
        $('div[nctype="map_nav"]').hide();
    });

    // 导航菜单默认值显示第一组菜单
    $('div[data-param^="map-"]:first').nextAll().hide();
    $('A[data-param^="map-"]:first').parent().addClass('selected');

    //管理显示与隐藏
    $("#admin-manager-btn").click(function () {
        if ($(".manager-menu").css("display") == "none") {
            $(".manager-menu").css('display', 'block'); 
            $("#admin-manager-btn").attr("title","关闭快捷管理"); 
            $("#admin-manager-btn").removeClass().addClass("arrow-close");
        }
        else {
            $(".manager-menu").css('display', 'none');
            $("#admin-manager-btn").attr("title","显示快捷管理");
            $("#admin-manager-btn").removeClass().addClass("arrow");
        }
    });

    $('.sub-menu').on('click focus', 'li a', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var data_id = $(this).data('id');
        try{
            var menuid = parseInt(data_id);
        if(menuid){
            $.cookie("menuid",menuid,{ path: '/' });
        }
        }catch(err){}
    });


});

// 点击菜单，iframe页面跳转
function openItem(param) {
    $('.sub-menu,.nc-module-menu').find('li').removeClass('active');
    data_str = param.split('|');
    $this = $('div[id^="admincpNavTabs_"]').find('a[data-param="' + param + '"]');
    if ($('.admincp-container').hasClass('unfold')) {
        $('.sub-menu').hide();
        $this.parents('dd:first').show();
    }
    $('div[id^="admincpNavTabs_"]').hide().find('dl').removeClass('active');
    $('li[data-param="' + data_str[0] + '"]').addClass('active');
    $this.parent().addClass('active').parents('dl:first').addClass('active').parents('div:first').show();
    $('#workspace').attr('src', data_str[1]);
    $.cookie('workspaceParam', data_str[1], { expires: 1 ,path:"/"});
}