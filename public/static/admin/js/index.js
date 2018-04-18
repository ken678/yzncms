//全局配置
layui.config({
    base: "/static/admin/js/"
}).extend({
    "bodyTab": "bodyTab"
})
//加载所需模块
layui.use(['bodyTab', 'form', 'element', 'layer', 'jquery'], function() {
    var $ = layui.jquery;
    var form = layui.form;
    var element = layui.element;
    var tab = layui.bodyTab({
        openTabNum: "10", //最大可打开窗口数量
        url: "/static/admin/js/navs.json" //获取菜单json地址
    });
    var layer = layui.layer;

    //通过顶部菜单获取左侧二三级菜单   注：此处只做演示之用，实际开发中通过接口传参的方式获取导航数据
    function getData(json){
        $.getJSON(tab.tabConfig.url,function(data){
            if(json == "contentManagement"){
                dataStr = data.contentManagement;
                //重新渲染左侧菜单
                tab.render();
            }else if(json == "memberCenter"){
                dataStr = data.memberCenter;
                //重新渲染左侧菜单
                tab.render();
            }else if(json == "systemeSttings"){
                dataStr = data.systemeSttings;
                //重新渲染左侧菜单
                tab.render();
            }else if(json == "seraphApi"){
                dataStr = data.seraphApi;
                //重新渲染左侧菜单
                tab.render();
            }
        })
    }
    getData("contentManagement");


    //通过顶部菜单获取左侧菜单
    $(".topLevelMenus li,.mobileTopLevelMenus dd").click(function() {
        if ($(this).parents(".mobileTopLevelMenus").length != "0") {
            $(".topLevelMenus li").eq($(this).index()).addClass("layui-this").siblings().removeClass("layui-this");
        } else {
            $(".mobileTopLevelMenus dd").eq($(this).index()).addClass("layui-this").siblings().removeClass("layui-this");
        }
        $(".layui-layout-admin").removeClass("showMenu");
        $("body").addClass("site-mobile");
        getData($(this).data("menu"));
        //渲染顶部窗口
        tab.tabMove();
    })


    //手机设备的简单适配
    var treeMobile = $('.site-tree-mobile'),
        shadeMobile = $('.site-mobile-shade')
    treeMobile.on('click', function() {
        $('body').addClass('site-mobile');
    });
    shadeMobile.on('click', function() {
        $('body').removeClass('site-mobile');
    });



})