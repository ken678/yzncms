define(['jquery', 'form'], function($, Form) {
    var Controller = {
        index: function() {
            require(['pearAdmin'], function(pearAdmin) {

                pearAdmin.render({
                    "menu": {
                        "data": Yzn.api.fixurl("index/index"),
                        "method": "POST",
                        "accordion": true,
                        "collapse": false,
                        "control": false, //菜单模式 false 为常规菜单，true 为多系统菜单
                        "select": 0,
                        "async": true
                    },
                    "tab": {
                        "enable": true,
                        "keepState": true,
                        "session": true,
                        "preload": true,
                        "max": "10",
                        "index": {
                            "id": 0,
                            "href": Yzn.api.fixurl("main/index"),
                            "title": "首页"
                        }
                    },
                    "theme": {
                        "defaultColor": "2",
                        "defaultMenu": "dark-theme",
                        "defaultHeader": "light-theme",
                        "allowCustom": true,
                        "banner": false
                    },
                    "colors": [
                        { "id": "1", "color": "#2d8cf0", "second": "#ecf5ff" },
                        { "id": "2", "color": "#36b368", "second": "#f0f9eb" },
                        { "id": "3", "color": "#f6ad55", "second": "#fdf6ec" },
                        { "id": "4", "color": "#f56c6c", "second": "#fef0f0" },
                        { "id": "5", "color": "#3963bc", "second": "#ecf5ff" }
                    ],
                    "other": {
                        "keepLoad": "1200",
                        "autoHead": false,
                        "footer": false
                    }
                });

                // 登出逻辑 
                pearAdmin.logout(function() {
                    location.href = Yzn.api.fixurl("index/logout");
                    // 注销逻辑 返回 true / false
                    return true;
                })

                //清除缓存
                $('body').on('click', "dl#deletecache dd a", function() {
                    $.ajax({
                        url: Yzn.api.fixurl("index/cache"),
                        dataType: 'json',
                        data: { type: $(this).data("type") },
                        cache: false,
                        success: function(ret) {
                            var msg = ret.hasOwnProperty("msg") && ret.msg != "" ? ret.msg : "";
                            if (ret.code == 1) {
                                var index = layer.msg('清除缓存中，请稍候', { icon: 16, time: false, shade: 0.8 });
                                setTimeout(function() {
                                    layer.close(index);
                                    Toastr.success(msg ? msg : "缓存清除成功！");
                                }, 1000);
                            } else {
                                Toastr.error(msg ? msg : '清除缓存失败');
                            }
                        },
                        error: function() {
                            Toastr.error(msg ? msg : '清除缓存失败');
                        }
                    });
                });
                window.pearAdmin = pearAdmin;
            })

        },
        login: function() {
            //让错误提示框居中
            Yzn.config.toastr.positionClass = "toast-top-center";

            Form.api.bindevent($(".layui-form"), function(data, res) {
                layer.msg('登入成功', {
                    offset: '15px',
                    icon: 1,
                    time: 1000
                }, function() {
                    window.location.href = Backend.api.fixurl(res.url);
                });
                return false;
            }, function(res) {
                $("#verify").click();
            });

            //刷新验证码
            $("#verify").click(function() {
                var verifyimg = $("#verify").attr("src");
                $("#verify").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
            });
        }
    };
    return Controller;
});