$(document).ready(function () {
    //使用title内容作为tooltip提示文字
    $(document).tooltip({
        track: true
    });

    //布局换色设置
    var bgColorSelectorColors = [{ c: '#981767', cName: '' }, { c: '#AD116B', cName: '' }, { c: '#B61944', cName: '' }, { c: '#AA1815', cName: '' }, { c: '#C4182D', cName: '' }, { c: '#D74641', cName: '' }, { c: '#ED6E4D', cName: '' }, { c: '#D78A67', cName: '' }, { c: '#F5A675', cName: '' }, { c: '#F8C888', cName: '' }, { c: '#F9D39B', cName: '' }, { c: '#F8DB87', cName: '' }, { c: '#FFD839', cName: '' }, { c: '#F9D12C', cName: '' }, { c: '#FABB3D', cName: '' }, { c: '#F8CB3C', cName: '' }, { c: '#F4E47E', cName: '' }, { c: '#F4ED87', cName: '' }, { c: '#DFE05E', cName: '' }, { c: '#CDCA5B', cName: '' }, { c: '#A8C03D', cName: '' }, { c: '#73A833', cName: '' }, { c: '#468E33', cName: '' }, { c: '#5CB147', cName: '' }, { c: '#6BB979', cName: '' }, { c: '#8EC89C', cName: '' }, { c: '#9AD0B9', cName: '' }, { c: '#97D3E3', cName: '' }, { c: '#7CCCEE', cName: '' }, { c: '#5AC3EC', cName: '' }, { c: '#16B8D8', cName: '' }, { c: '#49B4D6', cName: '' }, { c: '#6DB4E4', cName: '' }, { c: '#8DC2EA', cName: '' }, { c: '#BDB8DC', cName: '' }, { c: '#8381BD', cName: '' }, { c: '#7B6FB0', cName: '' }, { c: '#AA86BC', cName: '' }, { c: '#AA7AB3', cName: '' }, { c: '#935EA2', cName: '' }, { c: '#9D559C', cName: '' }, { c: '#C95C9D', cName: '' }, { c: '#DC75AB', cName: '' }, { c: '#EE7DAE', cName: '' }, { c: '#E6A5CA', cName: '' }, { c: '#EA94BE', cName: '' }, { c: '#D63F7D', cName: '' }, { c: '#C1374A', cName: '' }, { c: '#AB3255', cName: '' }, { c: '#A51263', cName: '' }, { c: '#7F285D', cName: ''}];
    $("#trace_show").click(function(){
        $("div.bgSelector").toggle(300, function() {
            if ($(this).html() == '') {
                $(this).sColor({
                    colors: bgColorSelectorColors,  // 必填，所有颜色 c:色号（必填） cName:颜色名称（可空）
                    colorsHeight: '31px',  // 必填，颜色的高度
                    curTop: '0', // 可选，颜色选择对象高偏移，默认0
                    curImg: ADMIN_TEMPLATES_URL + '/images/cur.png',  //必填，颜色选择对象图片路径
                    form: 'drag', // 可选，切换方式，drag或click，默认drag
                    keyEvent: true,  // 可选，开启键盘控制，默认true
                    prevColor: true, // 可选，开启切换页面后背景色是上一页面所选背景色，如不填则换页后背景色是defaultItem，默认false
                    defaultItem: ($.cookie('bgColorSelectorPosition') != null) ? $.cookie('bgColorSelectorPosition') : 22  // 可选，第几个颜色的索引作为初始颜色，默认第1个颜色
                });
            }
        });//切换显示
    });
    if ($.cookie('bgColorSelectorPosition') != null) {
        $('body').css('background-color', bgColorSelectorColors[$.cookie('bgColorSelectorPosition')].c);
    } else {
        $('body').css('background-color', bgColorSelectorColors[22].c);
    }

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
    data_str = param;
    $this = $('div[id^="admincpNavTabs_"]').find('a[data-param="' + param + '"]');
    if ($('.admincp-container').hasClass('unfold')) {
        $('.sub-menu').hide();
        $this.parents('dd:first').show();
    }
    $('div[id^="admincpNavTabs_"]').hide().find('dl').removeClass('active');
    //$('li[data-param="' + data_str + '"]').addClass('active');
    $this.parent().addClass('active').parents('dl:first').addClass('active').parents('div:first').show();
    $('#workspace').attr('src', param);
    $.cookie('workspaceParam', param, { expires: 1 ,path:"/"});
}