layui.define(['layer', 'laytpl', 'form', 'element', 'util'], function(exports) {
    var $ = layui.jquery,
        layer = layui.layer,
        laytpl = layui.laytpl,
        form = layui.form,
        element = layui.element,
        util = layui.util,
        device = layui.device()

    //阻止IE7以下访问
    if (device.ie && device.ie < 8) {
        layer.alert('如果您非得使用 IE 浏览器访问Fly社区，那么请使用 IE8+');
    }

    var fly = {
        //Ajax
        json: function(url, data, success, options) {
            var that = this,
                type = typeof data === 'function';

            if (type) {
                options = success
                success = data;
                data = {};
            }

            options = options || {};

            return $.ajax({
                type: options.type || 'post',
                dataType: options.dataType || 'json',
                data: data,
                url: url,
                success: function(res) {
                    if (res.code === 1) {
                        success && success(res);
                    } else {
                        layer.msg(res.msg || res.code, { shift: 6 });
                        options.error && options.error();
                    }
                },
                error: function(e) {
                    layer.msg('请求异常，请重试', { shift: 6 });
                    options.error && options.error(e);
                }
            });
        },

        //计算字符长度
        charLen: function(val) {
            var arr = val.split(''),
                len = 0;
            for (var i = 0; i < val.length; i++) {
                arr[i].charCodeAt(0) < 299 ? len++ : len += 2;
            }
            return len;
        },
    };


    //表单提交
    form.on('submit(*)', function(data) {
        var action = $(data.form).attr('action'),
            button = $(data.elem);
        fly.json(action, data.field, function(res) {
            var end = function() {
                if (res.url) {
                    location.href = res.url;
                } else {
                    fly.form[action || button.attr('key')](data.field, data.form);
                }
            };
            if (res.code == 1) {
                button.attr('alert') ? layer.alert(res.msg, {
                    icon: 1,
                    time: 10 * 1000,
                    end: end
                }) : end();
            };
        });
        return false;
    });

    //加载特定模块
    if (layui.cache.page && layui.cache.page !== 'index') {
        var extend = {};
        extend[layui.cache.page] = layui.cache.page;
        layui.extend(extend);
        layui.use(layui.cache.page);
    }


    //手机设备的简单适配
    var treeMobile = $('.site-tree-mobile'),
        shadeMobile = $('.site-mobile-shade')

    treeMobile.on('click', function() {
        $('body').addClass('site-mobile');
    });

    shadeMobile.on('click', function() {
        $('body').removeClass('site-mobile');
    });

    //验证码切换
    $('#codeimage').click(function() {
        var num = new Date().getTime();
        var rand = Math.round(Math.random() * 10000);
        var num = num + rand;
        $("#codeimage").attr('src', $("#codeimage").attr('src') + "&t=" + num);
    });

    exports('fly', fly);

});