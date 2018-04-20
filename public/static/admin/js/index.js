layui.use(['form', 'element', 'layer', 'jquery'], function() {
    var $ = layui.jquery;
    var form = layui.form;
    var element = layui.element;
    var layer = layui.layer;


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