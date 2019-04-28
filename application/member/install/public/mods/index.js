layui.define(['layer', 'laytpl', 'form', 'element', 'upload', 'util'], function(exports) {
    var $ = layui.jquery,
        layer = layui.layer,
        laytpl = layui.laytpl,
        form = layui.form,
        element = layui.element,
        upload = layui.upload,
        util = layui.util,
        device = layui.device(),
        DISABLED = 'layui-btn-disabled';

    //阻止IE7以下访问
    if (device.ie && device.ie < 10) {
        layer.alert('如果您非得使用 IE 浏览器访问，那么请使用 IE10+');
    }


    layui.focusInsert = function(obj, str) {
        var result, val = obj.value;
        obj.focus();
        if (document.selection) { //ie
            result = document.selection.createRange();
            document.selection.empty();
            result.text = str;
        } else {
            result = [val.substring(0, obj.selectionStart), str, val.substr(obj.selectionEnd)];
            obj.focus();
            obj.value = result.join('');
        }
    };

    //显示当前tab
    if (location.hash) {
        element.tabChange('user', location.hash.replace(/^#/, ''));
    }
    element.on('tab(user)', function() {
        var othis = $(this),
            layid = othis.attr('lay-id');
        if (layid) {
            location.hash = layid;
        }
    });

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
                    if (res.status === 0) {
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
        }

    }

    //表单提交
    form.on('submit(*)', function(data) {
        var action = $(data.form).attr('action'),
            button = $(data.elem);
        fly.json(action, data.field, function(res) {
            var end = function() {
                if (res.action) {
                    location.href = res.action;
                } else {
                    fly.form[action || button.attr('key')](data.field, data.form);
                }
            };
            if (res.status == 0) {
                button.attr('alert') ? layer.alert(res.msg, {
                    icon: 1,
                    time: 10 * 1000,
                    end: end
                }) : end();
            };
        });
        return false;
    });

    //上传图片
    if ($('.upload-img')[0]) {
        layui.use('upload', function(upload) {
            var avatarAdd = $('.avatar-add');
            upload.render({
                elem: '.upload-img',
                url: GV.image_upload_url,
                size: 50,
                before: function() {
                    avatarAdd.find('.loading').show();
                },
                done: function(res) {
                    console.log(res);
                    if (res.code == 0) {
                        $.post(GV.profile_upload_url, {
                            avatar: res.id
                        }, function(res) {
                            //location.reload();
                        });
                    } else {
                        layer.msg(res.info, { icon: 5 });
                    }
                    avatarAdd.find('.loading').hide();
                },
                error: function() {
                    avatarAdd.find('.loading').hide();
                }
            });
        });
    }

    /**
     * iframe弹窗
     * @href 弹窗地址
     * @title 弹窗标题
     * @lay-data {width: '弹窗宽度', height: '弹窗高度', idSync: '是否同步ID', table: '数据表ID(同步ID时必须)', type: '弹窗类型'}
     */
    $(document).on('click', '.layui-iframe', function() {
        var that = $(this),
            query = '';
        var def = { width: '750px', height: '500px', idSync: false, table: 'dataTable', type: 2, url: that.attr('href'), title: that.attr('title') };
        var opt = new Function('return ' + that.attr('lay-data'))() || {};

        opt.url = opt.url || def.url;
        opt.title = opt.title || def.title;
        opt.width = opt.width || def.width;
        opt.height = opt.height || def.height;
        opt.type = opt.type || def.type;
        opt.table = opt.table || def.table;
        opt.idSync = opt.idSync || def.idSync;

        if (!opt.url) {
            layer.msg('请设置href参数');
            return false;
        }

        if (opt.idSync) { // ID 同步
            if ($('.checkbox-ids:checked').length <= 0) {
                var checkStatus = table.checkStatus(opt.table);
                if (checkStatus.data.length <= 0) {
                    layer.msg('请选择要操作的数据');
                    return false;
                }

                for (var i in checkStatus.data) {
                    query += '&id[]=' + checkStatus.data[i].id;
                }
            } else {
                $('.checkbox-ids:checked').each(function() {
                    query += '&id[]=' + $(this).val();
                })
            }
        }

        if (opt.url.indexOf('?') >= 0) {
            opt.url += '&iframe=yes' + query;
        } else {
            opt.url += '?iframe=yes' + query;
        }

        layer.open({ type: opt.type, title: opt.title, content: opt.url, area: [opt.width, opt.height] });
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

    //固定Bar
    /*util.fixbar({
        bar1: '&#xe642;',
        bgcolor: '#009688',
        click: function(type) {
            if (type === 'bar1') {
                layer.msg('打开 index.js，开启发表新帖的路径');
                //location.href = 'jie/add.html';
            }
        }
    });*/

    exports('fly', fly);

});