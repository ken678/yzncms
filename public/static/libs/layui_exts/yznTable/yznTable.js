/**
 @ Name：简单封下table
 */
layui.define(['form', 'table', 'yzn', 'laydate', 'laytpl', 'element','notice'], function(exports) {
    var MOD_NAME = 'yznTable',
        $ = layui.$,
        table = layui.table,
        yzn = layui.yzn,
        laydate = layui.laydate,
        element = layui.element,
        laytpl = layui.laytpl,
        form = layui.form,
        notice = layui.notice;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTable',
    };

    var ColumnsForSearch = [];

    yznTable = {
        bindevent: function () {
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
                            id: ids
                        },
                    }, function(res) {
                        yzn.msg.success(res.msg, function() {
                            table.reload(tableId);
                        });
                    });
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
                var clienWidth = $(this).attr('data-width') || 800,
                    clientHeight = $(this).attr('data-height') || 600,
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
        },
        render: function(options) {
            options.init = options.init || init;
            options.modifyReload = yzn.parame(options.modifyReload, false);
            options.id = options.id || options.init.table_render_id;
            options.elem = options.elem || options.init.table_elem;
            options.cols = options.cols || [];
            options.layFilter = options.id + '_LayFilter';
            options.url = options.url || options.init.index_url;
            options.search = yzn.parame(options.search, true);
            options.searchFormVisible = yzn.parame(options.searchFormVisible, false);
            options.defaultToolbar = (options.defaultToolbar === undefined && !options.search) ? ['filter', 'print', 'exports'] : ['filter', 'print', 'exports', {
                title: '搜索',
                layEvent: 'TABLE_SEARCH',
                icon: 'layui-icon-search',
                extend: 'data-table-id="' + options.id + '"'
            }];
            options.even = options.even || true;
            // 判断是否为移动端
            if (yzn.checkMobile()) {
                options.defaultToolbar = !options.search ? ['filter'] : ['filter', {
                    title: '搜索',
                    layEvent: 'TABLE_SEARCH',
                    icon: 'layui-icon-search',
                    extend: 'data-table-id="' + options.id + '"'
                }];
            }
            options.searchInput = options.search ? yzn.parame(options.searchInput || options.init.searchInput, true) : false;

            var tableDone = options.done || function() {};
            options.done = function(res, curr, count) {
                tableDone(res, curr, count);
            };

            // 判断元素对象是否有嵌套的
            options.cols = yznTable.formatCols(options.cols, options.init);

            // 初始化表格lay-filter
            $(options.elem).attr('lay-filter', options.layFilter);

            // 初始化表格搜索
            if (options.search === true) {
                yznTable.renderSearch(options.cols, options.elem, options.id, options.searchFormVisible);
            }

            // 初始化表格左上方工具栏
            options.toolbar = options.toolbar || ['refresh', 'add', 'delete', 'export'];
            options.toolbar = yznTable.renderToolbar(options);

            var newTable = table.render(options);
            // 监听表格搜索开关显示
            yznTable.listenToolbar(options.layFilter, options.id);
            // 监听表格开关切换
            yznTable.renderSwitch(options.cols, options.init, options.id, options.modifyReload);
            // 监听表格文本框编辑
            yznTable.listenEdit(options.init, options.layFilter, options.id, options.modifyReload);
            return newTable;
        },
        renderToolbar: function(options) {
            var d = options.toolbar,
                tableId = options.id,
                searchInput = options.searchInput,
                init = options.init;
            d = d || [];
            var toolbarHtml = '';
            $.each(d, function(i, v) {
                if (v === 'refresh') {
                    toolbarHtml += '<button class="layui-btn layui-btn-sm yzn-btn-primary" data-table-refresh="' + tableId + '"><i class="iconfont icon-shuaxin1"></i> </button>\n';
                } else if (v === 'add') {
                    toolbarHtml += '<button class="layui-btn layui-btn-normal layui-btn-sm" data-open="' + init.add_url + '" data-title="添加"><i class="iconfont icon-add"></i> 添加</button>\n';
                } else if (v === 'delete') {
                    toolbarHtml += '<button class="layui-btn layui-btn-sm layui-btn-danger" data-href="' + init.delete_url + '" data-batch-all="' + tableId + '"><i class="iconfont icon-trash"></i> 删除</button>\n';
                } else if (typeof v === "object") {
                    $.each(v, function(ii, vv) {
                        vv.class = vv.class || '';
                        vv.icon = vv.icon || '';
                        vv.auth = vv.auth || '';
                        vv.url = vv.url || '';
                        vv.method = vv.method || 'open';
                        vv.title = vv.title || vv.text;
                        vv.text = vv.text || vv.title;
                        vv.extend = vv.extend || '';
                        vv.checkbox = vv.checkbox || false;
                        vv.html = vv.html || '';
                        toolbarHtml += yznTable.buildToolbarHtml(vv, tableId);
                    });
                }
            });
            if (searchInput) {
                toolbarHtml += '<input id="layui-input-search" value="" placeholder="搜索" class="layui-input layui-hide-xs" style="display:inline-block;width:auto;float: right;\n' + 'margin:2px 25px 0 0;height:28px;">\n'
            }
            return '<div>' + toolbarHtml + '</div>';
        },
        buildToolbarHtml: function(toolbar, tableId) {
            var html = '';
            toolbar.class = toolbar.class || '';
            toolbar.icon = toolbar.icon || '';
            toolbar.auth = toolbar.auth || '';
            toolbar.url = toolbar.url || '';
            toolbar.extend = toolbar.extend || '';
            toolbar.method = toolbar.method || 'open';
            toolbar.field = toolbar.field || 'id';
            toolbar.title = toolbar.title || toolbar.text;
            toolbar.text = toolbar.text || toolbar.title;
            toolbar.checkbox = toolbar.checkbox || false;
            toolbar.html = toolbar.html || '';

            if (toolbar.html !== '') {
                return toolbar.html;
            }

            var formatToolbar = toolbar;
            formatToolbar.icon = formatToolbar.icon !== '' ? '<i class="' + formatToolbar.icon + '"></i> ' : '';
            formatToolbar.class = formatToolbar.class !== '' ? 'class="' + formatToolbar.class + '" ' : '';

            if (toolbar.method === 'open') {
                formatToolbar.method = formatToolbar.method !== '' ? 'data-open="' + formatToolbar.url + '" data-title="' + formatToolbar.title + '" ' : '';
            } else {
                formatToolbar.method = formatToolbar.method !== '' ? 'data-request="' + formatToolbar.url + '" data-title="' + formatToolbar.title + '" ' : '';
            }

            formatToolbar.checkbox = toolbar.checkbox ? ' data-checkbox="true" ' : '';
            formatToolbar.tableId = tableId !== undefined ? ' data-table="' + tableId + '" ' : '';

            html = '<button ' + formatToolbar.class + formatToolbar.method + formatToolbar.extend + formatToolbar.checkbox + formatToolbar.tableId + '>' + formatToolbar.icon + formatToolbar.text + '</button>\n';
            return html;
        },
        renderSearch: function(cols, elem, tableId, searchFormVisible) {
            // TODO 只初始化第一个table搜索字段，如果存在多个(绝少数需求)，得自己去扩展
            cols = cols[0] || {};
            var newCols = [];
            var formHtml = '';
            $.each(cols, function(i, d) {
                d.field = d.field || false;
                d.fieldAlias = yzn.parame(d.fieldAlias, d.field);
                d.title = d.title || d.field || '';
                d.selectList = d.selectList || {};
                d.search = yzn.parame(d.search, true);
                d.searchTip = d.searchTip || '请输入' + d.title || '';
                d.searchValue = d.searchValue || '';
                d.searchOp = d.searchOp || '=';
                d.timeType = d.timeType || 'datetime';
                d.extend = typeof d.extend === 'undefined' ? '' : d.extend;
                d.addClass = typeof d.addClass === 'undefined' ? (typeof d.addclass === 'undefined' ? 'layui-input' : 'layui-input ' + d.addclass) : 'layui-input ' + d.addClass;
                if (d.field !== false && d.search !== false) {
                    ColumnsForSearch.push(d);
                    switch (d.search) {
                        case true:
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input type="hidden" class="form-control operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
                                '<input class="' + d.addClass + '" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '" value="' + d.searchValue + '" placeholder="' + d.searchTip + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'select':
                            d.searchOp = '=';
                            var selectHtml = '';
                            $.each(d.selectList, function(sI, sV) {
                                var selected = '';
                                if (sI === d.searchValue) {
                                    selected = 'selected=""';
                                }
                                selectHtml += '<option value="' + sI + '" ' + selected + '>' + sV + '</option>/n';
                            });
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input type="hidden" class="form-control operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
                                '<select class="layui-select" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '">\n' +
                                '<option value="">- 全部 -</option> \n' +
                                selectHtml +
                                '</select>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'range':
                            d.searchOp = 'range';
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input type="hidden" class="form-control operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
                                '<input class="' + d.addClass + '" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '" value="' + d.searchValue + '" placeholder="' + d.searchTip + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'time':
                            d.searchOp = '=';
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input class="' + d.addClass + '" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '"  data-search-op="' + d.searchOp + '"  value="' + d.searchValue + '" placeholder="' + d.searchTip + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'between':
                            d.searchOp = 'BETWEEN';
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<input type="hidden" class="form-control operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline" style="width: 80px;">\n' +
                                '<input type="text" name="' + d.fieldAlias + '" id="' + d.fieldAlias + '-min" placeholder="' + d.searchTip + '" autocomplete="off" class="' + d.addClass + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '<div class="layui-form-mid">-</div>\n' +
                                '<div class="layui-input-inline" style="width: 80px;">\n' +
                                '<input type="text" name="' + d.fieldAlias + '" id="' + d.fieldAlias + '-min" placeholder="' + d.searchTip + '" autocomplete="off" class="' + d.addClass + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                    }
                    newCols.push(d);
                }
            });
            if (formHtml !== '') {
                $(elem).before('<fieldset style="border:1px solid #ddd;" id="searchFieldset_' + tableId + '" class="table-search-fieldset ' + (searchFormVisible ? "" : "layui-hide") + '">\n' +
                    '<legend>条件搜索</legend>\n' +
                    '<form class="layui-form layui-form-pane form-search form-commonsearch">\n' +
                    formHtml +
                    '<div class="layui-form-item layui-inline" style="margin-left: 115px">\n' +
                    '<button type="submit" class="layui-btn layui-btn-normal" data-type="tableSearch" data-table="' + tableId + '" lay-submit lay-filter="' + tableId + '_filter"> 搜 索</button>\n' +
                    '<button type="reset" class="layui-btn layui-btn-primary" data-table-reset="' + tableId + '"> 重 置 </button>\n' +
                    ' </div>' +
                    '</form>' +
                    '</fieldset>');

                yznTable.listenTableSearch(tableId);

                // 初始化form表单
                form.render();
                $.each(newCols, function(ncI, ncV) {
                    if (ncV.search === 'range') {
                        laydate.render({ range: true, type: ncV.timeType, elem: '[name="' + ncV.field + '"]' });
                    }
                    if (ncV.search === 'time') {
                        laydate.render({ type: ncV.timeType, elem: '[name="' + ncV.field + '"]' });
                    }
                });
            }
        },
        formatCols: function(cols, init) {
            for (i in cols) {
                var col = cols[i];
                for (index in col) {
                    var val = col[index];

                    // 判断是否包含初始化数据
                    if (val.init === undefined) {
                        cols[i][index]['init'] = init;
                    }

                    // 格式化列操作栏
                    if (val.templet === yznTable.formatter.tool && val.operat === undefined) {
                        cols[i][index]['operat'] = ['edit', 'delete'];
                    }

                    // 判断是否包含开关组件
                    if (val.templet === yznTable.formatter.switch && val.filter === undefined) {
                        cols[i][index]['filter'] = val.field;
                    }

                    // 判断是否含有搜索下拉列表
                    if (val.selectList !== undefined && val.search === undefined) {
                        cols[i][index]['search'] = 'select';
                    }

                    // 判断是否初始化对齐方式
                    if (val.align === undefined) {
                        cols[i][index]['align'] = 'left';
                    }

                    // 部分字段开启排序
                    var sortDefaultFields = ['id', 'sort'];
                    if (val.sort === undefined && sortDefaultFields.indexOf(val.field) >= 0) {
                        cols[i][index]['sort'] = true;
                    }

                    // 初始化图片高度
                    if (val.templet === yznTable.formatter.image && val.imageHeight === undefined) {
                        cols[i][index]['imageHeight'] = 30;
                    }

                    // 判断是否多层对象
                    if (val.field !== undefined && val.field.split(".").length > 1) {
                        if (val.templet === undefined) {
                            cols[i][index]['templet'] = yznTable.formatter.value;
                        }
                    }

                    // 判断是否列表数据转换
                    /*if (val.selectList !== undefined && val.templet === undefined) {
                        cols[i][index]['templet'] = yznTable.formatter.list;
                    }*/
                }
            }
            return cols;
        },
        listenTableSearch: function(tableId) {
            form.on('submit(' + tableId + '_filter)', function(data) {
                var searchQuery = yznTable.getSearchQuery(this, true);


                table.reload(tableId, {
                    page: {
                        curr: 1
                    },
                    where: {
                        filter: JSON.stringify(searchQuery.filter),
                        op: JSON.stringify(searchQuery.op)
                    }
                });
                return false;
            })
            $(document).on('blur', '#layui-input-search', function(event) {
                var text = $(this).val();
                table.reload(tableId, { where: { search: text } });
                $('#layui-input-search').prop("value", $(this).val());
                return false
            })
        },
        getSearchQuery: function(that, removeempty) {
            var op = {};
            var filter = {};
            var value = '';
            $("form.form-commonsearch .operate").each(function(i) {
                var name = $(this).data("name");
                var sym = $(this).is("select") ? $("option:selected", this).val() : $(this).val().toUpperCase();
                var obj = $("[name='" + name + "']", '.form-commonsearch');
                if (obj.size() == 0)
                    return true;
                var vObjCol = ColumnsForSearch[i];
                var process = vObjCol && typeof vObjCol.process == 'function' ? vObjCol.process : null;
                if (obj.size() > 1) {
                    if (/BETWEEN$/.test(sym)) {
                        var value_begin = $.trim($("[name='" + name + "']:first", that.$commonsearch).val()),
                            value_end = $.trim($("[name='" + name + "']:last", that.$commonsearch).val());
                        if (value_begin.length || value_end.length) {
                            if (process) {
                                value_begin = process(value_begin, 'begin');
                                value_end = process(value_end, 'end');
                            }
                            value = value_begin + ',' + value_end;
                        } else {
                            value = '';
                        }
                        //如果是时间筛选，将operate置为RANGE
                        if ($("[name='" + name + "']:first", '.form-commonsearch').hasClass("datetimepicker")) {
                            sym = 'RANGE';
                        }
                    } else {
                        value = $("[name='" + name + "']:checked", '.form-commonsearch').val();
                        value = process ? process(value) : value;
                    }
                } else {
                    value = process ? process(obj.val()) : obj.val();
                }
                if (removeempty && (value == '' || value == null || ($.isArray(value) && value.length == 0)) && !sym.match(/null/i)) {
                    return true;
                }
                op[name] = sym;
                filter[name] = value;
            });
            return { op: op, filter: filter };
        },
        listenToolbar: function(layFilter, tableId) {
            table.on('toolbar(' + layFilter + ')', function(obj) {
                // 搜索表单的显示
                switch (obj.event) {
                    case 'TABLE_SEARCH':
                        var searchFieldsetId = 'searchFieldset_' + tableId;
                        var _that = $("#" + searchFieldsetId);
                        if (_that.hasClass("layui-hide")) {
                            _that.removeClass('layui-hide');
                        } else {
                            _that.addClass('layui-hide');
                        }
                        break;
                }
            });
        },
        renderSwitch: function (cols, tableInit, tableId, modifyReload) {
            tableInit.modify_url = tableInit.modify_url || false;
            cols = cols[0] || {};
            tableId = tableId || init.table_render_id;
            if (cols.length > 0) {
                $.each(cols, function (i, v) {
                    v.filter = v.filter || false;
                    if (v.filter !== false && tableInit.modify_url !== false) {
                        yznTable.listenSwitch({filter: v.filter, url: tableInit.modify_url, tableId: tableId, modifyReload: modifyReload});
                    }
                });
            }
        },
        listenSwitch: function (option, ok) {
            option.filter = option.filter || '';
            option.url = option.url || '';
            option.field = option.field || option.filter || '';
            option.tableId = option.tableId || init.table_render_id;
            option.modifyReload = option.modifyReload || false;
            form.on('switch(' + option.filter + ')', function (obj) {
                var checked = obj.elem.checked ? 1 : 0;
                if (typeof ok === 'function') {
                    return ok({
                        id: obj.value,
                        checked: checked,
                    });
                } else {
                    var data = {
                        id: obj.value,
                        param: option.field,
                        value: checked,
                    };
                    yzn.request.post({
                        url: option.url,
                        prefix: true,
                        data: data,
                    }, function (res) {
                        notice.success({ message: res.msg });
                        if (option.modifyReload) {
                            table.reload(option.tableId);
                        }
                    }, function (res) {
                        yzn.msg.error(res.msg, function () {
                            table.reload(option.tableId);
                        });
                    }, function () {
                        table.reload(option.tableId);
                    });
                }
            });
        },
        listenEdit: function(tableInit, layFilter, tableId, modifyReload) {
            tableInit.modify_url = tableInit.modify_url || false;
            tableId = tableId || init.table_render_id;
            if (tableInit.modify_url !== false) {
                table.on('edit(' + layFilter + ')', function(obj) {
                    var value = obj.value,
                        data = obj.data,
                        id = data.id,
                        field = obj.field;
                    var _data = {
                        id: id,
                        param: field,
                        value: value,
                    };
                    yzn.request.post({
                        url: tableInit.modify_url,
                        prefix: true,
                        data: _data,
                    }, function(res) {
                        notice.success({ message: res.msg });
                        if (modifyReload) {
                            table.reload(tableId);
                        }
                    }, function(res) {
                        yzn.msg.error(res.msg, function() {
                            table.reload(tableId);
                        });
                    }, function() {
                        table.reload(tableId);
                    });
                });
            }
        },
        toolSpliceUrl(url, field, data) {
            url = url.indexOf("?") !== -1 ? url + '&' + field + '=' + data[field] : url + '?' + field + '=' + data[field];
            return url;
        },
        buildOperatHtml: function(operat) {
            var html = '';
            operat.class = operat.class || '';
            operat.icon = operat.icon || '';
            //operat.auth = operat.auth || '';
            operat.url = operat.url || '';
            operat.extend = operat.extend || '';
            operat.method = operat.method || 'open';
            operat.field = operat.field || 'id';
            operat.title = operat.title || operat.text;
            operat.text = operat.text || operat.title;

            var formatOperat = operat;
            formatOperat.icon = formatOperat.icon !== '' ? '<i class="' + formatOperat.icon + '"></i> ' : '';
            formatOperat.class = formatOperat.class !== '' ? 'class="' + formatOperat.class + '" ' : '';
            if (operat.method === 'open') {
                formatOperat.method = formatOperat.method !== '' ? 'data-open="' + formatOperat.url + '" data-title="' + formatOperat.title + '" ' : '';
            } else if (operat.method === 'href'){
                formatOperat.method = formatOperat.method !== '' ? 'href="' + formatOperat.url + '" data-title="' + formatOperat.title + '" ' : '';
            } else if (operat.method === 'none'){ // 常用于与extend配合，自定义监听按钮
                formatOperat.method = '';
            } else {
                formatOperat.method = formatOperat.method !== '' ? 'data-request="' + formatOperat.url + '" data-title="' + formatOperat.title + '" ' : '';
            }
            html = '<a ' + formatOperat.class + formatOperat.method + formatOperat.extend + '>' + formatOperat.icon + formatOperat.text + '</a>';
            return html;
        },
        formatter: {
            tool: function(data) {
                var that = this;
                that.operat = that.operat || ['edit', 'delete'];
                var elem = that.init.table_elem || init.table_elem;
                var html = '';
                $.each(that.operat, function(i, item) {
                    if (typeof item === 'string') {
                        switch (item) {
                            case 'edit':
                                var operat = {
                                    class: 'layui-btn layui-btn-success layui-btn-xs',
                                    method: 'open',
                                    field: 'id',
                                    icon: '',
                                    text: "<i class='iconfont icon-brush_fill'></i>",
                                    title: '编辑信息',
                                    url: that.init.edit_url,
                                    extend: ""
                                };
                                operat.url = yznTable.toolSpliceUrl(operat.url, operat.field, data);
                                //if (admin.checkAuth(operat.auth, elem)) {
                                html += yznTable.buildOperatHtml(operat);
                                //}
                                break;
                            case 'delete':
                                var operat = {
                                    class: 'layui-btn layui-btn-danger layui-btn-xs layui-tr-del',
                                    method: 'href',
                                    field: 'id',
                                    icon: '',
                                    text: "<i class='iconfont icon-trash_fill'></i>",
                                    title: '',
                                    url: that.init.delete_url,
                                    extend: ""
                                };
                                operat.url = yznTable.toolSpliceUrl(operat.url, operat.field, data);
                                //if (admin.checkAuth(operat.auth, elem)) {
                                html += yznTable.buildOperatHtml(operat);
                                //}
                                break;
                        }

                    } else if (typeof item === 'object') {
                        $.each(item, function(i, operat) {
                            operat.class = operat.class || '';
                            operat.icon = operat.icon || '';
                            operat.auth = operat.auth || '';
                            operat.url = operat.url || '';
                            operat.method = operat.method || 'open';
                            operat.field = operat.field || 'id';
                            operat.title = operat.title || operat.text;
                            operat.text = operat.text || operat.title;
                            operat.extend = operat.extend || '';
                            operat.url = yznTable.toolSpliceUrl(operat.url, operat.field, data);
                            //if (admin.checkAuth(operat.auth, elem)) {
                            html += yznTable.buildOperatHtml(operat);
                            //}
                        });
                    }
                });
                return html;
            },
            flag: function (data) {
                var that = this;
                var field = that.field;
                try {
                    var value = eval("data." + field);
                    value = value == null || value.length === 0 ? '' : value.toString();
                } catch (e) {
                    var value = undefined;
                }
                //赤色 墨绿 蓝色 藏青 雅黑 橙色
                var colorArr = {0:'red',1:'green',2:'blue',3:'cyan',4:'black',5:'orange'};
                //如果字段列有定义custom
                if (typeof that.custom !== 'undefined') {
                    colorArr = $.extend(colorArr, that.custom);
                }

                //渲染Flag
                var html = [];
                var arr = value != '' ? value.split(',') : [];
                var color, display, label;
                $.each(arr, function (i, value) {
                    value = value == null || value.length === 0 ? '' : value.toString();
                    if (value == '')
                        return true;
                    color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'green';
                    display = typeof that.selectList !== 'undefined' && typeof that.selectList[value] !== 'undefined' ? that.selectList[value] : value.charAt(0).toUpperCase() + value.slice(1);
                    label = '<span class="layui-badge layui-bg-' + color + '">' + display + '</span>';
                    html.push(label);
                })
                return html.join(' ');
            },
            label: function (data) {
                return yznTable.formatter.flag.call(this, data);
            },
            switch: function (data) {
                var that = this;
                var field = that.field;
                that.filter = that.filter || that.field || null;
                that.checked = that.checked || 1;
                that.tips = that.tips || '开|关';
                try {
                    var value = eval("data." + field);
                } catch (e) {
                    var value = undefined;
                }
                var checked = value === that.checked ? 'checked' : '';
                return laytpl('<input type="checkbox" name="' + that.field + '" value="' + data.id + '" lay-skin="switch" lay-text="' + that.tips + '" lay-filter="' + that.filter + '" ' + checked + ' >').render(data);
            },
            image: function(data) {
                var that = this;
                that.imageWidth = that.imageWidth || 80;
                that.imageHeight = that.imageHeight || 30;
                that.imageSplit = that.imageSplit || '|';
                that.imageJoin = that.imageJoin || '<br>';
                that.title = that.title || that.field;
                var field = that.field,
                    title = data[that.title];
                try {
                    var value = eval("data." + field);
                } catch (e) {
                    var value = undefined;
                }
                if (!value) {
                    return '';
                } else {
                    var values = value.split(that.imageSplit),
                        valuesHtml = [];
                    values.forEach((value, index) => {
                        valuesHtml.push('<img style="max-width: ' + that.imageWidth + 'px; max-height: ' + that.imageHeight + 'px;" src="' + value + '" data-image="' + title + '">');
                    });
                    return valuesHtml.join(that.imageJoin);
                }
            },
            url: function(data) {
                var that = this;
                var field = that.field;
                try {
                    var value = eval("data." + field);
                } catch (e) {
                    var value = undefined;
                }
                return '<a class="layui-btn layui-btn-primary layui-btn-xs" href="' + value + '" target="_blank"><i class="iconfont icon-lianjie"></i></a></a>';
            },
            price: function(data) {
                var that = this;
                var field = that.field;
                try {
                    var value = eval("data." + field);
                } catch (e) {
                    var value = undefined;
                }
                return '<span>￥' + value + '</span>';
            },
            icon: function(data) {
                var that = this;
                var field = that.field;
                try {
                    var value = eval("data." + field);
                } catch (e) {
                    var value = undefined;
                }
                return '<i class="' + value + '"></i>';
            },
            text: function(data) {
                var that = this;
                var field = that.field;
                try {
                    var value = eval("data." + field);
                } catch (e) {
                    var value = undefined;
                }
                return '<span class="line-limit-length">' + value + '</span>';
            },
            value: function(data) {
                var that = this;
                var field = that.field;
                try {
                    var value = eval("data." + field);
                } catch (e) {
                    var value = undefined;
                }
                return '<span>' + value + '</span>';
            },
        },

    }

    yznTable.bindevent();
    exports(MOD_NAME, yznTable);
});