/**
 * http://www.apache.org/licenses/LICENSE-2.0
 * Copyright (c) 2018 http://yzncms.com All rights reserved.
 * Author: 御宅男 <530765310@qq.com>
 * Original reference: https://gitee.com/karson/fastadmin
 */
define(['yzn'], function(Yzn) {
    var Backend = {
        api: {
            replaceids: function(elem, url) {
                //如果有需要替换ids的
                if (url.indexOf("{ids}") > -1) {
                    var ids = 0;
                    var tableId = $(elem).data("table");
                    if (tableId && $("#" + tableId).length > 0) {
                        var Table = require("table");
                        ids = Table.api.selectedids(tableId).join(",");
                    }
                    url = url.replace(/\{ids\}/g, ids);
                }
                return url;
            },
            addtabs: function(url, title, id) {
                url = Yzn.api.fixurl(url);
                if (parent.pearAdmin) {
                    parent.pearAdmin.addTab(id, title, url);
                } else if (window.top.pearAdmin) {
                    window.top.pearAdmin.addTab(id, title, url);
                }

            }
        },
        init: function() {
            //配置Toastr的参数
            Toastr.options.positionClass = Config.controllername === 'index' ? "toast-top-right-index" : "toast-top-right";
            //点击包含.btn-dialog的元素时弹出dialog
            $(document).on('click', '.btn-dialog,.dialogit', function(e) {
                var that = this;
                var options = $.extend({}, $(that).data() || {});
                var url = Backend.api.replaceids(that, $(that).data("url") || $(that).attr('href'));
                var title = $(that).attr("title") || $(that).data("title") || $(that).data('original-title');

                if (typeof options.confirm !== 'undefined') {
                    Layer.confirm(options.confirm, function(index) {
                        Backend.api.open(url, title, options);
                        Layer.close(index);
                    });
                } else {
                    window[$(that).data("window") || 'self'].Backend.api.open(url, title, options);
                }
                return false;
            });
            //点击包含.btn-addtabs的元素时新增选项卡
            $(document).on('click', '.btn-addtabs,.addtabsit', function(e) {
                var that = this;
                var options = $.extend({}, $(that).data() || {});
                var url = Backend.api.replaceids(that, $(that).data("url") || $(that).attr('href'));
                var title = $(that).attr("title") || $(that).data("title") || $(that).data('original-title');
                var id = $(that).data("menu-id") || Math.floor(new Date().valueOf() * Math.random());

                if (!$(this).attr('data-menu-id')) {
                    $(this).attr('data-menu-id', id);
                }
                if (typeof options.confirm !== 'undefined') {
                    Layer.confirm(options.confirm, function(index) {
                        Backend.api.addtabs(url, title, id);
                        Layer.close(index);
                    });
                } else {
                    Backend.api.addtabs(url, title, id);
                }
                return false;
            });
            //点击包含.btn-ajax的元素时发送Ajax请求
            $(document).on('click', '.btn-ajax,.ajaxit', function(e) {
                var that = this;
                var options = $.extend({}, $(that).data() || {});
                if (typeof options.url === 'undefined' && $(that).attr("href")) {
                    options.url = $(that).attr("href");
                }
                options.url = Backend.api.replaceids(this, options.url);
                var success = typeof options.success === 'function' ? options.success : null;
                var error = typeof options.error === 'function' ? options.error : null;
                delete options.success;
                delete options.error;

                //如果未设备成功的回调,设定了自动刷新的情况下自动进行刷新
                if (!success && typeof options.table !== 'undefined' && typeof options.refresh !== 'undefined' && options.refresh) {
                    success = function() {
                        layui.table.reload(options.table);
                    }
                }
                if (typeof options.confirm !== 'undefined') {
                    Layer.confirm(options.confirm, function(index) {
                        Backend.api.ajax(options, success, error);
                        Layer.close(index);
                    });
                } else {
                    Backend.api.ajax(options, success, error);
                }
                return false;
            });
            // 放大图片
            $('body').on('click', '[data-image]', function() {
                var title = $(this).attr('data-image'),
                    src = $(this).attr('src'),
                    alt = $(this).attr('alt');
                var photos = {
                    "title": title,
                    "id": Math.random(),
                    "data": [{
                        "alt": alt,
                        "pid": Math.random(),
                        "src": src,
                        "thumb": src
                    }]
                };
                Layer.photos({
                    photos: photos,
                    anim: 5
                });
                return false;
            });
            var tips_index = 0;
            $(document).on('mouseenter', '[lay-tips]', function() {
                tips_index = layer.tips($(this).attr('lay-tips'), this, {
                    tips: 1,
                    time: 0
                });
            }).on('mouseleave', '[lay-tips]', function() {
                layer.close(tips_index);
            });
            //修复含有fixed-footer类的body边距
            if ($(".fixed-footer").length > 0) {
                $(document.body).css("padding-bottom", $(".fixed-footer").outerHeight());
            }
            //修复不在iframe时layer-footer隐藏的问题
            if ($(".layer-footer").length > 0 && self === top) {
                $(".layer-footer").show();
            }
        }

    }
    Backend.api = $.extend(Yzn.api, Backend.api);
    //将Backend渲染至全局,以便于在子框架中调用
    window.Backend = Backend;
    Backend.init();
    return Backend;

})