//封装表单操作 部分参考EasyAdmin和fastadmin
layui.define(['layer', 'form', 'yzn', 'table', 'notice', 'element', 'dragsort'], function(exports) {
    var MOD_NAME = 'yznForm',
        $ = layui.$,
        layer = layui.layer,
        yzn = layui.yzn,
        table = layui.table,
        form = layui.form,
        dragsort = layui.dragsort,
        element = layui.element,
        notice = layui.notice;

    // 文件上传集合
    var webuploaders = [];
    // 当前上传对象
    var curr_uploader = {};

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTable',
    };

    var yznForm = {
        listen: function(preposeCallback, ok, no, ex) {
            // 监听表单提交事件
            yznForm.api.formSubmit(preposeCallback, ok, no, ex);
        },
        api: {
            form: function(url, data, ok, no, ex, refresh, type, pop) {
                var that = this;
                var submitBtn = $(".layer-footer button[lay-submit]");
                submitBtn.addClass("layui-btn-disabled").prop('disabled', true);
                if (refresh === undefined) {
                    refresh = true;
                }
                yzn.request.post({
                    url: url,
                    data: data,
                }, function(res) {
                    setTimeout(function() {
                        submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    }, 3000);
                    if (false === $('.layui-form').triggerHandler("success.form")) {
                        return false;
                    }
                    if (typeof ok === 'function') {
                        if (false === ok.call($(this), res)) {
                            return false;
                        }
                    }
                    if (type === 'layui-form') {
                        res.msg = res.msg || '';
                        yzn.msg.success(res.msg);
                        setTimeout(function() {
                            if (pop === true) {
                                if (refresh == true) {
                                    parent.location.reload();
                                } else if (typeof(res.url) != 'undefined' && res.url != null && res.url != '') {
                                    parent.location.href = res.url;
                                }
                                parent.layui.layer.closeAll();
                            } else if (refresh == true) {
                                if (typeof(res.url) != 'undefined' && res.url != null && res.url != '') {
                                    location.href = res.url;
                                } else {
                                    //history.back(-1);
                                    location.reload();
                                }
                            }
                        }, 3000);
                        return false;
                    } else {
                        res.msg = res.msg || '';
                        yzn.msg.success(res.msg, function() {
                            yznForm.api.closeCurrentOpen({
                                refreshTable: refresh
                            });
                        });
                        return false;
                    }
                }, function(res) {
                    submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    if (false === $('.layui-form').triggerHandler("error.form")) {
                        return false;
                    }
                    if (typeof no === 'function') {
                        if (false === no.call($(this), res)) {
                            return false;
                        }
                    }
                    var msg = res.msg == undefined ? '返回数据格式有误' : res.msg;
                    yzn.msg.error(msg);
                    return false;
                }, function(res) {
                    submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    if (typeof ex === 'function') {
                        if (false === ex.call($(this), res)) {
                            return false;
                        }
                    }
                    return false;
                });
                return false;
            },
            closeCurrentOpen: function(option) {
                option = option || {};
                option.refreshTable = option.refreshTable || false;
                option.refreshFrame = option.refreshFrame || false;
                if (option.refreshTable === true) {
                    option.refreshTable = init.table_render_id;
                }
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                if (option.refreshTable !== false) {
                    parent.layui.table.reload(option.refreshTable);
                }
                if (option.refreshFrame) {
                    parent.location.reload();
                }
                return false;
            },
            refreshFrame: function() {
                parent.location.reload();
                return false;
            },
            refreshTable: function(tableName) {
                tableName = tableName | 'currentTable';
                table.reload(tableName);
            },
            formSubmit: function(preposeCallback, ok, no, ex) {
                var formList = document.querySelectorAll("[lay-submit]");

                // 表单提交自动处理
                if (formList.length > 0) {
                    $.each(formList, function(i, v) {
                        var filter = $(this).attr('lay-filter'),
                            type = $(this).attr('data-type'),
                            refresh = $(this).attr('data-refresh'),
                            pop = $(this).attr('data-pop'),
                            url = $(this).attr('lay-submit');
                        // 表格搜索不做自动提交
                        if (type === 'tableSearch') {
                            return false;
                        }
                        // 判断是否需要刷新表格
                        if (refresh === 'false') {
                            refresh = false;
                        } else {
                            refresh = true;
                        }
                        if (pop === 'true') {
                            pop = true;
                        } else {
                            pop = false;
                        }
                        // 自动添加layui事件过滤器
                        if (filter === undefined || filter === '') {
                            filter = 'save_form_' + (i + 1);
                            $(this).attr('lay-filter', filter)
                        }
                        if (url === undefined || url === '' || url === null) {
                            url = window.location.href;
                        }
                        form.on('submit(' + filter + ')', function(data) {
                            //var dataField = data.field;
                            var dataField = $(data.form).serialize();

                            // 富文本数据处理
                            /*var editorList = document.querySelectorAll(".editor");
                            if (editorList.length > 0) {
                                $.each(editorList, function(i, v) {
                                    var name = $(this).attr("name");
                                    dataField[name] = CKEDITOR.instances[name].getData();
                                });
                            }*/

                            if (typeof preposeCallback === 'function') {
                                dataField = preposeCallback(dataField);
                            }
                            yznForm.api.form(url, dataField, ok, no, ex, refresh, type, pop);
                            return false;
                        });
                    });
                }

            },
        },
        events: {
            init: function() {
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
                    layer.photos({
                        photos: photos,
                        anim: 5
                    });
                    return false;
                });

                // 监听动态表格刷新
                $('body').on('click', '[data-table-refresh]', function() {
                    var tableId = $(this).attr('data-table-refresh');
                    if (tableId === undefined || tableId === '' || tableId == null) {
                        tableId = init.table_render_id;
                    }
                    table.reload(tableId);
                });

                // 列表页批量操作按钮组
                $('body').on('click', '[data-batch-all]', function() {
                    var that = $(this),
                        tableId = that.attr('data-batch-all'),
                        url = that.attr('data-href');
                    tableId = tableId || init.table_render_id;
                    url = url !== undefined ? url : window.location.href;
                    var checkStatus = table.checkStatus(tableId),
                        data = checkStatus.data;
                    if (data.length <= 0) {
                        yzn.msg.error('请选择要操作的数据');
                        return false;
                    }
                    var ids = [];
                    $.each(data, function(i, v) {
                        ids.push(v.id);
                    });
                    yzn.msg.confirm('您确定要执行此操作吗？', function() {
                        yzn.request.post({
                            url: url,
                            data: {
                                ids: ids
                            },
                        }, function(res) {
                            yzn.msg.success(res.msg, function() {
                                table.reload(tableId);
                            });
                        });
                    });
                    return false;
                });

                // 监听请求
                $('body').on('click', '[data-request]', function() {
                    var that = $(this);
                    var title = $(this).data('title'),
                        url = $(this).data('request'),
                        tableId = $(this).data('table'),
                        checkbox = $(this).data('checkbox');

                    var postData = {};
                    if (checkbox === 'true') {
                        tableId = tableId || init.table_render_id;
                        var checkStatus = table.checkStatus(tableId),
                            data = checkStatus.data;
                        if (data.length <= 0) {
                            yzn.msg.error('请勾选需要操作的数据');
                            return false;
                        }
                        var ids = [];
                        $.each(data, function(i, v) {
                            ids.push(v.id);
                        });
                        postData.id = ids;
                    }

                    title = title || '确定进行该操作？';
                    tableId = tableId || init.table_render_id;

                    func = function() {
                        yzn.request.post({
                            url: url,
                            data: postData,
                        }, function(res) {
                            yzn.msg.success(res.msg, function() {
                                tableId && table.reload(tableId);
                            });
                        })
                    }
                    if (typeof(that.attr('confirm')) == 'undefined') {
                        func();
                    } else {
                        yzn.msg.confirm(title, func);
                    }
                    return false;
                });

                // 监听弹出层的打开
                $('body').on('click', '[data-open]', function() {
                    var clienWidth = $(this).attr('data-width'),
                        clientHeight = $(this).attr('data-height'),
                        dataFull = $(this).attr('data-full'),
                        checkbox = $(this).attr('data-checkbox'),
                        url = $(this).attr('data-open'),
                        title = $(this).attr("title") || $(this).data("title"),
                        tableId = $(this).attr('data-table');

                    if (checkbox === 'true') {
                        tableId = tableId || init.table_render_id;
                        var checkStatus = table.checkStatus(tableId),
                            data = checkStatus.data;
                        if (data.length <= 0) {
                            yzn.msg.error('请勾选需要操作的数据');
                            return false;
                        }
                        var ids = [];
                        $.each(data, function(i, v) {
                            ids.push(v.id);
                        });
                        if (url.indexOf("?") === -1) {
                            url += '?id=' + ids.join(',');
                        } else {
                            url += '&id=' + ids.join(',');
                        }
                    }
                    if (clienWidth === undefined || clientHeight === undefined) {
                        clienWidth = $(window).width() >= 800 ? '800px' : '100%';
                        clientHeight = $(window).height() >= 600 ? '600px' : '100%';

                    }
                    if (dataFull === 'true') {
                        clienWidth = '100%';
                        clientHeight = '100%';
                    }
                    yzn.open(title, url, clienWidth, clientHeight);
                });

                //通用状态设置开关
                form.on('switch(switchStatus)', function(data) {
                    var that = $(this),
                        status = 0;
                    if (!that.attr('data-href')) {
                        notice.info({ message: '请设置data-href参数' });
                        return false;
                    }
                    if (this.checked) {
                        status = 1;
                    }
                    $.get(that.attr('data-href'), { value: status }, function(res) {
                        if (res.code === 1) {
                            notice.success({ message: res.msg });
                        } else {
                            notice.error({ message: res.msg });
                            that.trigger('click');
                            form.render('checkbox');
                        }
                    });
                });

                //单行表格删除(不刷新)
                $(document).on('click', '.layui-tr-del', function() {
                    var that = $(this),
                        index = that.parents('tr').eq(0).data('index'),
                        tr = $('.layui-table-body').find('tr[data-index="' + index + '"]'),
                        href = !that.attr('data-href') ? that.attr('href') : that.attr('data-href');
                    layer.confirm('删除之后无法恢复，您确定要删除吗？', { icon: 3, title: '提示信息' }, function(index) {
                        if (!href) {
                            notice.info({ message: '请设置data-href参数' });
                            return false;
                        }
                        $.get(href, function(res) {
                            if (res.code == 1) {
                                notice.success({ message: res.msg });
                                //that.parents('tr').remove();
                                tr.remove();
                            } else {
                                notice.error({ message: res.msg });
                            }
                        });
                        layer.close(index);
                    });
                    return false;
                });

                /**
                 * 普通按钮点击iframe弹窗
                 * @href 弹窗地址
                 * @title 弹窗标题
                 * @lay-data {width: '弹窗宽度', height: '弹窗高度', idSync: '是否同步ID', table: '数据表ID(同步ID时必须)', type: '弹窗类型'}
                 */
                $(document).on('click', '.layui-iframe', function() {
                    var that = $(this),
                        query = '';
                    var def = { width: '750px', height: '500px', idSync: false, table: 'dataTable', type: 2, url: !that.attr('data-href') ? that.attr('href') : that.attr('data-href'), title: that.attr('title') };
                    var opt = new Function('return ' + that.attr('lay-data'))() || {};

                    opt.url = opt.url || def.url;
                    opt.title = opt.title || def.title;
                    opt.width = opt.width || def.width;
                    opt.height = opt.height || def.height;
                    opt.type = opt.type || def.type;
                    opt.table = opt.table || def.table;
                    opt.idSync = opt.idSync || def.idSync;

                    if (!opt.url) {
                        notice.info({ message: '请设置data-href参数' });
                        return false;
                    }

                    if (opt.idSync) { // ID 同步
                        if ($('.checkbox-ids:checked').length <= 0) {
                            var checkStatus = table.checkStatus(opt.table);
                            if (checkStatus.data.length <= 0) {
                                notice.info({ message: '请选择要操作的数据' });
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
            },
            faselect: function() {
                // 绑定图片选择组件
                if ($('.layui-form .fachoose-image').length > 0) {
                    layui.define('tableSelect', function(exports) {
                        var tableSelect = layui.tableSelect;
                        $.each($('.layui-form .fachoose-image'), function(i, v) {
                            var that = this;
                            var input_id = $(this).data("input-id") ? $(this).data("input-id") : "",
                                inputObj = $("#" + input_id),
                                //inputObj2 = $("#" + input_id+'_text'),
                                multiple = $(this).data("multiple") ? 'checkbox' : 'radio';
                            //var $file_list = $('#file_list_' + input_id);
                            tableSelect.render({
                                elem: "#fachoose-" + input_id,
                                searchKey: 'name',
                                searchPlaceholder: '请输入图片名称',
                                table: {
                                    url: GV.image_select_url,
                                    cols: [
                                        [
                                            { type: multiple },
                                            { field: 'id', title: 'ID' },
                                            { field: 'url', minWidth: 120, search: false, title: '图片', imageHeight: 40, align: "center", templet: '<div><img src="{{d.path}}" height="100%"></div>' },
                                            { field: 'name', width: 120, title: '名称' },
                                            { field: 'mime', width: 120, title: 'Mime类型' },
                                            { field: 'create_time', width: 180, title: '上传时间', align: "center", search: 'range' },
                                        ]
                                    ]
                                },
                                done: function(e, data) {
                                    var selectedList = [];
                                    $.each(data.data, function(index, val) {
                                        selectedList[index] = {
                                            file_path: val.path
                                        };
                                    });
                                    selectedList.forEach(function(item) {
                                        if (multiple == 'checkbox') {
                                            if (inputObj.val()) {
                                                inputObj.val(inputObj.val() + ',' + item.file_path).trigger('change');
                                            } else {
                                                inputObj.val(item.file_path).trigger('change');
                                            }
                                        } else {
                                            inputObj.val(item.file_path).trigger('change');
                                        }
                                    });
                                }
                            })
                        });
                    })
                }
            },
            fieldlist: function() {
                // 绑定fieldlist组件
                if ($(".layui-form .fieldlist").size() > 0) {
                    layui.define('laytpl', function(exports) {
                        var laytpl = layui.laytpl;
                        //刷新隐藏textarea的值
                        var refresh = function(name, obj) {
                            var data = {};
                            var textarea = $("textarea[name='" + name + "']");
                            var container = $(".fieldlist[data-name='" + name + "']");
                            var template = container.data("template");
                            $.each($("input,select,textarea", container).serializeArray(), function(i, j) {
                                var reg = /\[(\w+)\]\[(\w+)\]$/g;
                                var match = reg.exec(j.name);
                                if (!match)
                                    return true;
                                match[1] = "x" + parseInt(match[1]);
                                if (typeof data[match[1]] == 'undefined') {
                                    data[match[1]] = {};
                                }
                                if (typeof obj != 'undefined' && $(obj.elem).attr('name') == j.name) {
                                    data[match[1]][match[2]] = obj.value;
                                } else {
                                    data[match[1]][match[2]] = j.value;
                                }
                            });
                            var result = template ? [] : {};
                            $.each(data, function(i, j) {
                                if (j) {
                                    if (!template) {
                                        if (j.key != '') {
                                            result[j.key] = j.value;
                                        }
                                    } else {
                                        result.push(j);
                                    }
                                }
                            });
                            textarea.val(JSON.stringify(result));
                        };
                        //监听文本框改变事件
                        $(document).on('change keyup changed', ".fieldlist input,.fieldlist textarea,.fieldlist select", function() {
                            refresh($(this).closest(".fieldlist").data("name"));
                        });
                        form.on('radio(fieldlist)', function(data) {
                            refresh($(this).closest(".fieldlist").data("name"), data);
                        });
                        form.on('select(fieldlist)', function(data) {
                            refresh($(this).closest(".fieldlist").data("name"), data);
                        });
                        //追加控制
                        $(".layui-form .fieldlist").on("click", ".btn-append,.append", function(e, row) {
                            var container = $(this).closest(".fieldlist");
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            var index = container.data("index");
                            var name = container.data("name");
                            var id = container.data("id");
                            var template = container.data("template");
                            var data = container.data();
                            index = index ? parseInt(index) : 0;
                            container.data("index", index + 1);
                            row = row ? row : {};
                            var vars = {
                                lists: [{ 'index': index, 'name': name, 'data': data, 'row': row }]
                            };
                            laytpl($("#" + id + "Tpl").html()).render(vars, function(html) {
                                $(html).attr("fieldlist-item", true).insertBefore($(tagName + ":last", container));
                            });
                            form.render();
                            yznForm.events.faselect();
                            $(this).trigger("fa.event.appendfieldlist", $(this).closest(tagName).prev());
                        });
                        //移除控制
                        $(".layui-form .fieldlist").on("click", ".btn-remove", function() {
                            var container = $(this).closest(".fieldlist");
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            $(this).closest(tagName).remove();
                            refresh(container.data("name"));
                        });
                        //渲染数据&拖拽排序
                        $(".layui-form .fieldlist").each(function() {
                            var container = this;
                            var tagName = $(this).data("tag") || ($(this).is("table") ? "tr" : "dd");
                            $(this).dragsort({
                                itemSelector: tagName,
                                dragSelector: ".btn-dragsort",
                                dragEnd: function() {
                                    refresh($(this).closest(".fieldlist").data("name"));
                                },
                                //placeHolderTemplate: '<div style="border:1px #009688 dashed;"></div>'
                            });

                            var textarea = $("textarea[name='" + $(this).data("name") + "']");
                            if (textarea.val() == '') {
                                return true;
                            }
                            var template = $(this).data("template");
                            textarea.on("fa.event.refreshfieldlist", function() {
                                $("[fieldlist-item]", container).remove();
                                var json = {};
                                try {
                                    json = JSON.parse($(this).val());
                                } catch (e) {}
                                $.each(json, function(i, j) {
                                    $(".btn-append,.append", container).trigger('click', template ? j : {
                                        key: i,
                                        value: j
                                    });
                                });
                            });
                            textarea.trigger("fa.event.refreshfieldlist");
                        });
                    })
                }
            },
            selectpage: function() {
                // 绑定selectpage组件
                if ($(".layui-form .selectpage").size() > 0) {
                    layui.define('selectPage', function(exports) {
                        var selectPage = layui.selectPage;
                        $('.layui-form .selectpage').selectPage({
                            eAjaxSuccess: function(data) {
                                //console.log(data);
                                data.list = typeof data.data !== 'undefined' ? data.data : [];
                                data.totalRow = typeof data.count !== 'undefined' ? data.count : data.data.length;
                                return data;
                            }
                        })
                    })
                }
            },
            citypicker: function() {
                // 绑定城市选择组件
                if ($("[data-toggle='city-picker']").size() > 0) {
                    layui.define('citypicker', function(exports) {
                        var citypicker = layui.citypicker;
                    })
                }
            },
            datetimepicker: function() {
                // 绑定时间组件
                if ($(".layui-form .datetime").size() > 0) {
                    layui.define('laydate', function(exports) {
                        var laydate = layui.laydate;
                        $(".layui-form .datetime").each(function() {
                            var format = $(this).attr('data-date'),
                                type = $(this).attr('data-date-type'),
                                range = $(this).attr('data-date-range');
                            if (type === undefined || type === '' || type === null) {
                                type = 'datetime';
                            }
                            var options = {
                                elem: this,
                                type: type,
                                trigger: 'click',
                            };
                            if (format !== undefined && format !== '' && format !== null) {
                                options['format'] = format;
                            }
                            if (range !== undefined) {
                                if (range === null || range === '') {
                                    range = '-';
                                }
                                options['range'] = range;
                            }
                            laydate.render(options);
                        });
                    })
                }
            },
            tagsinput: function() {
                // 绑定tags标签组件
                if ($(".layui-form .form-tags").size() > 0) {
                    layui.define('tagsinput', function(exports) {
                        var tagsinput = layui.tagsinput;
                        $('.form-tags').each(function() {
                            $(this).tagsInput({
                                width: 'auto',
                                defaultText: $(this).data('remark'),
                                height: '26px',
                            })
                        })
                    })
                }
            },
            colorpicker: function() {
                // 绑定颜色组件
                if ($('.layui-form .layui-color-box').length > 0) {
                    layui.define('colorpicker', function(exports) {
                        var colorpicker = layui.colorpicker;
                        $('.layui-color-box').each(function() {
                            var input_id = $(this).data("input-id");
                            var inputObj = $("#" + input_id);
                            colorpicker.render({
                                elem: $(this),
                                color: inputObj.val(),
                                done: function(color) {
                                    inputObj.val(color);
                                }
                            });
                        });
                    })
                }
            },
            ueditor: function() {
                // ueditor编辑器集合
                var ueditors = {};
                // 绑定ueditor编辑器组件
                if ($(".layui-form .js-ueditor").size() > 0) {
                    layui.define('ueditor', function(exports) {
                        var ueditor = layui.ueditor;
                        $('.layui-form .js-ueditor').each(function() {
                            var ueditor_name = $(this).attr('id');
                            ueditors[ueditor_name] = UE.getEditor(ueditor_name, {
                                initialFrameWidth: '100%',
                                initialFrameHeight: 400, //初始化编辑器高度,默认320
                                autoHeightEnabled: false, //是否自动长高
                                maximumWords: 50000, //允许的最大字符数
                                serverUrl: GV.ueditor_upload_url,
                            });
                            $('#' + ueditor_name + 'grabimg').click(function() {
                                var con = ueditors[ueditor_name].getContent();
                                $.post(GV.ueditor_grabimg_url, { 'content': con, 'type': 'images' },
                                    function(data) {
                                        ueditors[ueditor_name].setContent(data);
                                        layer.msg("图片本地化完成", { icon: 1 });
                                    }, 'html');
                            });
                            //分词检测
                            if (ueditor_name == 'content') {
                                $('#getwords').click(function() {
                                    var con = ueditors['content'].getContentTxt();
                                    $.post(GV.ueditor_getwords_url, { 'content': con },
                                        function(data) {
                                            if (data.code == 0) {
                                                $(".tags-keywords").importTags(data.arr);
                                                //$(".tags-keywords").val(data.arr);
                                            } else {
                                                layer.msg(data.msg, { icon: 2 });
                                            }
                                        });
                                });
                            }
                            //过滤敏感字
                            $('#' + ueditor_name + 'filterword').click(function() {
                                var con = ueditors[ueditor_name].getContent();
                                $.post(GV.filter_word_url, { 'content': con }).success(function(res) {
                                    if (res.code == 0) {
                                        if ($.isArray(res.data)) {
                                            layer.msg("违禁词：" + res.data.join(","), { icon: 2 });
                                        }
                                    } else {
                                        layer.msg("内容没有违禁词！", { icon: 1 });
                                    }
                                })
                            })
                        });
                    })
                }
            },
            cropper: function() {
                //裁剪图片
                $(document).on('click', '.cropper', function() {
                    var inputId = $(this).attr("data-input-id");
                    var image = $(this).parent('.file-panel').prev('img').data('original');
                    //var dataId = $(this).data("id");
                    var index = layer.open({
                        type: 2,
                        shadeClose: true,
                        shade: false,
                        area: ['880px', '620px'],
                        title: '图片裁剪',
                        content: GV.jcrop_upload_url + '?url=' + image,
                        success: function(layero, index) {
                            $(layero).data("arr", [inputId, image]);
                        }
                    });
                });
            },
            xmSelect: function() {
                // 绑定下拉框多选组件
                if ($('.layui-form .form-selects').length > 0) {
                    layui.define('xmSelect', function(exports) {
                        var xmselect = layui.xmSelect;
                        $('.layui-form .form-selects').each(function() {
                            var name = $(this).data("name");
                            var list = $(this).data("list");
                            var value = $(this).data("value");

                            var newArr = [];
                            $.each(list, function(i, j) {
                                var vote = {};
                                vote.value = i;
                                vote.name = j;
                                newArr.push(vote);
                            })
                            xmSelect.render({
                                el: document.querySelector('.form-selects'),
                                initValue: value !== "" ? value.split(',') : [],
                                name: name,
                                data: newArr
                            })
                        })
                    })
                }
            },
            upload_image: function(elements, onUploadSuccess, onUploadError) {
                elements = typeof elements === 'undefined' ? document.body : elements;
                // 绑定图片上传组件
                if ($(elements).length > 0) {
                    layui.link(layui.cache.base + 'webuploader/webuploader.css?v=0.1.8');
                    layui.define('webuploader', function(exports) {
                        var webuploader = layui.webuploader;
                        //分片
                        var chunking = typeof GV.site.chunking !== "undefined" ? GV.site.chunking : false,
                            chunkSize = typeof GV.site.chunksize !== "undefined" ? GV.site.chunksize : 5242880;
                        $(elements).each(function() {
                            var GUID = WebUploader.Base.guid();
                            if ($(this).attr("initialized")) {
                                return true;
                            }
                            $(this).attr("initialized", true);
                            var that = this;
                            var id = $(this).prop("id") || $(this).prop("name");
                            // 是否多图片上传
                            var multiple = $(that).data('multiple');
                            var type = $(that).data('type');
                            if (type == 'image') {
                                var formData = { thumb: GV.site.upload_thumb_water, watermark: GV.site.upload_thumb_water_pic };
                            } else {
                                var formData = chunking ? { chunkid: GUID } : {};
                            }
                            //填充ID
                            var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
                            //预览ID
                            var preview_id = $(that).data("preview-id") ? $(that).data("preview-id") : "";
                            var previewtpl = '<li class="file-item thumbnail"><img data-image data-original="{{d.url}}" src="{{d.url}}"><div class="file-panel">' + (multiple ? '<i class="iconfont icon-yidong move-picture"></i>' : '') + '<i class="iconfont icon-tailor cropper" data-input-id="' + input_id + '"></i> <i class="iconfont icon-trash remove-picture"></i></div></li>';
                            // 允许上传的后缀
                            var $ext = type == 'image' ? GV.site.upload_image_ext : GV.site.upload_file_ext;
                            // 图片限制大小
                            var $size = type == 'image' ? GV.site.upload_image_size * 1024 : GV.site.upload_file_size * 1024;
                            // 优化retina, 在retina下这个值是2
                            /*var ratio = window.devicePixelRatio || 1;
                            // 缩略图大小
                            var thumbnailWidth = 100 * ratio;
                            var thumbnailHeight = 100 * ratio;*/

                            var uploader = WebUploader.create({
                                // 选完图片后，是否自动上传。
                                auto: true,
                                // 去重
                                duplicate: true,
                                // 不压缩图片
                                resize: false,
                                compress: false,
                                pick: {
                                    id: '#' + id,
                                    multiple: multiple
                                },
                                chunked: chunking,
                                chunkSize: chunkSize,
                                server: type == 'image' ? GV.image_upload_url : GV.file_upload_url,
                                // 图片限制大小
                                fileSingleSizeLimit: $size,
                                // 只允许选择图片文件。
                                accept: {
                                    title: type == 'image' ? 'Images' : 'Files',
                                    extensions: $ext,
                                    mimeTypes: type == 'image' ? 'image/jpg,image/jpeg,image/bmp,image/png,image/gif' : '',
                                },
                                // 自定义参数
                                formData: formData,
                            })

                            element.on('tab', function(data) {
                                uploader.refresh();
                            });
                            //console.log(uploader);
                            // 当有文件添加进来的时候
                            /*uploader.on('fileQueued', function(file) {
                                var $li = $(
                                        '<div id="' + file.id + '" class="file-item js-gallery thumbnail">' +
                                        '<img data-image>' +
                                        '<div class="info">' + file.name + '</div>' +
                                        '<div class="file-panel">' +
                                        (multiple ? ' <i class="iconfont icon-yidong move-picture"></i> ' : '') +
                                        '<i class="iconfont icon-tailor cropper"></i> <i class="iconfont icon-trash remove-picture"></i></div><div class="progress progress-mini remove-margin active">' +
                                        '<div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>' +
                                        '</div>' +
                                        '<div class="file-state img-state"><div class="layui-bg-blue">正在读取...</div>' +
                                        '</div>'
                                    ),
                                    $img = $li.find('img');

                                if ($multiple) {
                                    $file_list.append($li);
                                } else {
                                    $file_list.html($li);
                                    $input_file.val('');
                                }
                                // 创建缩略图
                                // 如果为非图片文件，可以不用调用此方法。
                                // thumbnailWidth x thumbnailHeight 为 100 x 100
                                uploader.makeThumb(file, function(error, src) {
                                    if (error) {
                                        $img.replaceWith('<span>不能预览</span>');
                                        return;
                                    }
                                    $img.attr('src', src);
                                }, thumbnailWidth, thumbnailHeight);
                                // 设置当前上传对象
                                curr_uploader = uploader;
                            });*/

                            // 文件上传过程中创建进度条实时显示。
                            uploader.on('uploadProgress', function(file, percentage) {
                                $(that).find('.webuploader-pick').html("<i class='layui-icon layui-icon-upload'></i> 上传" + Math.floor(percentage * 100) + "%");
                            });

                            // 文件上传成功
                            uploader.on('uploadSuccess', function(file, response) {

                                var ok = function(file, response) {
                                    var button = $('#' + file.id);
                                    if (button) {
                                        //如果有文本框则填充
                                        if (input_id) {
                                            var urlArr = [];
                                            var inputObj = $("#" + input_id);
                                            if (multiple && inputObj.val() !== "") {
                                                urlArr.push(inputObj.val());
                                            }
                                            urlArr.push(response.url);
                                            inputObj.val(urlArr.join(",")).trigger("change");
                                        }
                                    }
                                }
                                if (response.code == 0) {
                                    if (type == 'file' && chunking) {
                                        //合并
                                        $.ajax({
                                            url: GV.file_upload_url,
                                            dataType: "json",
                                            type: "POST",
                                            data: {
                                                chunkid: GUID,
                                                action: 'merge',
                                                filesize: file.size,
                                                filename: file.name,
                                                id: file.id,
                                                chunks: Math.floor(file.size / chunkSize + (file.size % chunkSize > 1 ? 1 : 0)),
                                            },
                                            success: function(res) {
                                                ok(file, res);
                                            },
                                        })
                                    } else {
                                        ok(file, response);
                                    }
                                } else {
                                    yzn.msg.error(response.info);
                                }
                                if (typeof onUploadSuccess === 'function') {
                                    var result = onUploadSuccess.call(file, response);
                                    if (result === false)
                                        return;
                                }
                            });
                            // 文件上传失败，显示上传出错。
                            /*uploader.on('uploadError', function(file) {
                                var $li = $('#' + file.id);
                                $li.find('.file-state').html('<div class="layui-bg-red">服务器错误</div>');
                            });*/
                            // 完成上传完了，成功或者失败，先删除进度条。
                            uploader.on('uploadComplete', function(file) {
                                setTimeout(function() {
                                    $(that).find('.webuploader-pick').html("<i class='layui-icon layui-icon-upload'></i> 上传");
                                    uploader.refresh();
                                }, 500);
                            });
                            // 文件验证不通过
                            uploader.on('error', function(type) {
                                switch (type) {
                                    case 'Q_TYPE_DENIED':
                                        layer.alert('图片类型不正确，只允许上传后缀名为：' + $ext + '，请重新上传！', { icon: 5 })
                                        break;
                                    case 'F_EXCEED_SIZE':
                                        layer.alert('图片不得超过' + ($size / 1024) + 'kb，请重新上传！', { icon: 5 })
                                        break;
                                }
                            });
                            // 如果是多图上传，则实例化拖拽
                            if (preview_id && multiple) {
                                $("#" + preview_id).dragsort({
                                    dragSelector: ".move-picture",
                                    dragEnd: function() {
                                        $("#" + preview_id).trigger("fa.preview.change");
                                    },
                                    placeHolderTemplate: '<li class="file-item thumbnail" style="border:1px #009688 dashed;"></li>'
                                })
                            }
                            //刷新隐藏textarea的值
                            var refresh = function(name) {

                            }
                            if (preview_id && input_id) {
                                layui.define('laytpl', function(exports) {
                                    var laytpl = layui.laytpl;
                                    $(document.body).on("keyup change", "#" + input_id, function(e) {
                                        var inputStr = $("#" + input_id).val();
                                        var inputArr = inputStr.split(/\,/);
                                        $("#" + preview_id).empty();
                                        var tpl = $("#" + preview_id).data("template") ? $("#" + preview_id).data("template") : "";
                                        var extend = $("#" + preview_id).next().is("textarea") ? $("#" + preview_id).next("textarea").val() : "{}";
                                        var json = {};
                                        try {
                                            json = JSON.parse(extend);
                                        } catch (e) {}
                                        $.each(inputArr, function(i, j) {
                                            if (!j) {
                                                return true;
                                            }
                                            var suffix = /[\.]?([a-zA-Z0-9]+)$/.exec(j);
                                            suffix = suffix ? suffix[1] : 'file';
                                            var value = (json && typeof json[i] !== 'undefined' ? json[i] : null);
                                            var data = { url: j, data: $(that).data(), key: i, index: i, value: value, row: value, suffix: suffix };
                                            laytpl(previewtpl).render(data, function(html) {
                                                $("#" + preview_id).append(html);
                                            });
                                        });
                                        refresh($("#" + preview_id).data("name"));
                                    });
                                    $("#" + input_id).trigger("change");
                                })
                            }
                            if (preview_id) {
                                //监听文本框改变事件
                                $("#" + preview_id).on('change keyup', "input,textarea,select", function() {
                                    refresh($(this).closest("ul").data("name"));
                                });
                                // 监听事件
                                $(document.body).on("fa.preview.change", "#" + preview_id, function() {
                                    var urlArr = [];
                                    $("#" + preview_id + " [data-original]").each(function(i, j) {
                                        urlArr.push($(this).data("original"));
                                    });
                                    if (input_id) {
                                        $("#" + input_id).val(urlArr.join(","));
                                    }
                                    refresh($("#" + preview_id).data("name"));
                                });
                                // 移除按钮事件
                                $(document.body).on("click", "#" + preview_id + " .remove-picture", function() {
                                    $(this).closest("li").remove();
                                    $("#" + preview_id).trigger("fa.preview.change");
                                });
                            }
                            // 将上传实例存起来
                            webuploaders.push(uploader);
                        });
                    })
                }
            },
            /*upload_file: function (elements, onUploadSuccess, onUploadError) {
                elements = typeof elements === 'undefined' ? document.body : elements;
                // 绑定文件上传组件
                if ($(elements).length > 0) {
                    layui.define('webuploader', function(exports) {
                        var webuploader = layui.webuploader;
                        //分片
                        var chunking = GV.site.chunking, chunkSize = GV.site.chunksize || 5242880;

                        $(elements).each(function() {
                            var GUID = WebUploader.Base.guid();
                            var formData = chunking ? { chunkid: GUID } : {};
                            if ($(this).attr("initialized")) {
                               return true;
                            }
                            $(this).attr("initialized", true);
                            var that = this;
                            var $input_file = $(this).find('input');
                            var $input_file_name = $input_file.attr('id');
                            // 是否多文件上传
                            var $multiple = $input_file.data('multiple');
                            // 允许上传的后缀
                            var $ext = GV.site.upload_file_ext;
                            // 图片限制大小
                            var $size = GV.site.upload_file_size*1024;
                            // 文件列表
                            var $file_list = $('#file_list_' + $input_file_name);
                            // 实例化上传
                            var uploader = WebUploader.create({
                                // 选完文件后，是否自动上传。
                                auto: true,
                                // 去重
                                duplicate: true,
                                // 文件接收服务端。
                                server: GV.file_upload_url,
                                // 选择文件的按钮。可选。
                                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                                pick: {
                                    id: '#picker_' + $input_file_name,
                                    multiple: $multiple
                                },
                                chunked:chunking,
                                chunkSize:chunkSize,
                                // 文件限制大小
                                fileSingleSizeLimit: $size,
                                // 只允许选择文件文件。
                                accept: {
                                    title: 'Files',
                                    extensions: $ext
                                },
                                formData: formData
                            });

                            element.on('tab', function(data){
                                 uploader.refresh();
                            });

                            // 当有文件添加进来的时候
                            uploader.on('fileQueued', function(file) {
                                var $li = '<tr id="' + file.id + '" class="file-item"><td>' + file.name + '</td>' +
                                    '<td class="file-state">正在读取文件信息...</td><td><div class="layui-progress"><div class="layui-progress-bar" lay-percent="0%"></div></div></td>' +
                                    '<td><a href="javascript:void(0);" class="layui-btn download-file layui-btn layui-btn-xs">下载</a> <a href="javascript:void(0);" class="layui-btn remove-file layui-btn layui-btn-xs layui-btn-danger">删除</a></td></tr>';

                                if ($multiple) {
                                    $file_list.find('.file-box').append($li);
                                } else {
                                    $file_list.find('.file-box').html($li);
                                    // 清空原来的数据
                                    $input_file.val('');
                                }
                                // 设置当前上传对象
                                curr_uploader = uploader;
                            });

                            // 文件上传成功
                            uploader.on('uploadSuccess', function(file, response) {
                                function ok(file, response) {
                                    var $li = $('#' + file.id);
                                    if (response.code == 0) {
                                        if ($multiple) {
                                            if ($input_file.val()) {
                                                $input_file.val($input_file.val() + ',' + response.id);
                                            } else {
                                                $input_file.val(response.id);
                                            }
                                            $li.find('.remove-file').attr('data-id', response.id);
                                        } else {
                                            $input_file.val(response.id);
                                        }
                                    }
                                    // 加入提示信息
                                    $li.find('.file-state').html('<span class="">' + response.info + '</span>');
                                    // 添加下载链接
                                    $li.find('.download-file').attr('href', response.path);
                                    if (typeof onUploadSuccess === 'function') {
                                        var result = onUploadSuccess.call(file, response);
                                        if (result === false)
                                            return;
                                    }
                                }
                                if(chunking){
                                    //合并
                                    $.ajax({
                                        url:GV.file_upload_url,
                                        dataType:"json",
                                        type:"POST",
                                        data: {
                                            chunkid: GUID,
                                            action: 'merge',
                                            filesize: file.size,
                                            filename: file.name,
                                            id: file.id,
                                            chunks: Math.floor(file.size / chunkSize + (file.size % chunkSize > 1 ? 1 : 0)),
                                        },
                                        success:function(res){
                                            ok(file, res);
                                        },
                                    })
                                }else{
                                    ok(file, response);
                                }
                            });

                            // 文件上传过程中创建进度条实时显示。
                            uploader.on('uploadProgress', function(file, percentage) {
                                var $percent = $('#' + file.id).find('.layui-progress-bar');
                                $percent.css('width', percentage * 100 + '%');
                            });

                            // 文件上传失败，显示上传出错。
                            uploader.on('uploadError', function(file) {
                                var $li = $('#' + file.id);
                                $li.find('.file-state').html('<span class="text-danger">服务器发生错误~</span>');
                            });

                            // 文件验证不通过
                            uploader.on('error', function(type) {
                                switch (type) {
                                    case 'Q_TYPE_DENIED':
                                        layer.alert('文件类型不正确，只允许上传后缀名为：' + $ext + '，请重新上传！', { icon: 5 })
                                        break;
                                    case 'F_EXCEED_SIZE':
                                        layer.alert('文件不得超过' + ($size / 1024) + 'kb，请重新上传！', { icon: 5 })
                                        break;
                                }
                            });
                            // 删除文件
                            $file_list.delegate('.remove-file', 'click', function() {
                                if ($multiple) {
                                    var id = $(this).data('id'),
                                        ids = $input_file.val().split(',');

                                    if (id) {
                                        for (var i = 0; i < ids.length; i++) {
                                            if (ids[i] == id) {
                                                ids.splice(i, 1);
                                                break;
                                            }
                                        }
                                        $input_file.val(ids.join(','));
                                    }
                                } else {
                                    $input_file.val('');
                                }
                                $(this).closest('.file-item').remove();
                            });
                            // 将上传实例存起来
                            webuploaders.push(uploader);
                        });
                    })
                }
            }*/
        },
        bindevent: function() {
            var events = yznForm.events;
            events.init();
            events.selectpage();
            events.fieldlist();
            events.faselect();
            events.citypicker();
            events.datetimepicker();
            events.tagsinput();
            events.colorpicker();
            events.ueditor();
            events.cropper();
            events.xmSelect();
            events.upload_image('.webUpload');
            //events.upload_file('.js-upload-file,.js-upload-files');
        }
    }
    yznForm.bindevent();

    //修复含有fixed-footer类的body边距
    if ($(".fixed-footer").size() > 0) {
        $(document.body).css("padding-bottom", $(".fixed-footer").outerHeight());
    }
    //修复不在iframe时layer-footer隐藏的问题
    if ($(".layer-footer").size() > 0 && self === top) {
        $(".layer-footer").show();
    }

    /**
     * 监听表单提交
     * @attr action 请求地址
     * @attr data-form 表单DOM
     */
    form.on('submit(layui-Submit)', function(data) {
        var _form = '',
            that = $(this),
            text = that.text(),
            opt = {},
            def = { pop: false, refresh: true, jump: false, callback: null, time: 3000 };
        if ($(this).attr('data-form')) {
            _form = $(that.attr('data-form'));
        } else {
            _form = that.parents('form');
        }
        if (that.attr('lay-data')) {
            opt = new Function('return ' + that.attr('lay-data'))();
        }
        opt = Object.assign({}, def, opt);

        that.prop('disabled', true);
        yzn.request.post({
            url: _form.attr('action'),
            data: _form.serialize(),
        }, function(res) {
            if (opt.callback) {
                opt.callback(that, res);
            } else {
                layer.msg(res.msg, { icon: 1 });
                //that.text(res.msg);
                setTimeout(function() {
                    that.text(text).prop('disabled', false);
                    if (opt.pop == true) {
                        if (opt.refresh == true) {
                            parent.location.reload();
                        } else if (opt.jump == true && res.url != '') {
                            parent.location.href = res.url;
                        }
                        parent.layui.layer.closeAll();
                    } else if (opt.refresh == true) {
                        if (res.url != '') {
                            location.href = res.url;
                        } else {
                            history.back(-1);
                        }
                    }
                }, opt.time);
            }
        }, function(res) {
            layer.msg(res.msg, { icon: 2 });
            //that.text(res.msg).removeClass('layui-btn-normal').addClass('layui-btn-danger');
            setTimeout(function() {
                that.prop('disabled', false);
                //that.prop('disabled', false).removeClass('layui-btn-danger').text(text);
            }, opt.time);
        });
        return false;
    });

    //通用表单post提交
    $('.ajax-post').on('click', function(e) {
        var target, query, _form,
            target_form = $(this).attr('target-form'),
            that = this,
            nead_confirm = false;

        _form = $('.' + target_form);
        if ($(this).attr('hide-data') === 'true') {
            _form = $('.hide-data');
            query = _form.serialize();
        } else if (_form.get(0) == undefined) {
            return false;
        } else if (_form.get(0).nodeName == 'FORM') {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            if ($(this).attr('url') !== undefined) {
                target = $(this).attr('url');
            } else {
                target = _form.attr("action");
            }
            query = _form.serialize();
        } else if (_form.get(0).nodeName == 'INPUT' || _form.get(0).nodeName == 'SELECT' || _form.get(0).nodeName == 'TEXTAREA') {
            _form.each(function(k, v) {
                if (v.type == 'checkbox' && v.checked == true) {
                    nead_confirm = true;
                }
            })
            if (nead_confirm && $(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = _form.serialize();
        } else {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = _form.find('input,select,textarea').serialize();
        }

        $.post(target, query).success(function(data) {
            if (data.code == 1) {
                parent.layui.layer.closeAll();
                if (data.url) {
                    layer.msg(data.msg + ' 页面即将自动跳转~');
                } else {
                    layer.msg(data.msg);
                }
                setTimeout(function() {
                    if (data.url) {
                        location.href = data.url;
                    } else {
                        location.reload();
                    }
                }, 1500);
            } else {
                layer.msg(data.msg);
                setTimeout(function() {
                    if (data.url) {
                        location.href = data.url;
                    }
                }, 1500);
            }
        });
        return false;
    });

    exports(MOD_NAME, yznForm);
});