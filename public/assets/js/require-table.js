/**
 * http://www.apache.org/licenses/LICENSE-2.0
 * Copyright (c) 2018 http://yzncms.com All rights reserved.
 * Author: 御宅男 <530765310@qq.com>
 * Original reference: https://gitee.com/zhongshaofa/easyadmin
 * Original reference: https://gitee.com/karson/fastadmin
 */
define(['jquery', 'layui'], function($, layui) {
    var table = layui.table,
        laydate = layui.laydate,
        element = layui.element,
        laytpl = layui.laytpl,
        form = layui.form;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTable',
    };

    var ColumnsForSearch = [];

    var Table = {
        config: {
            //refreshbtn: '.btn-refresh',
            //addbtn: '.btn-add',
            //editbtn: '.btn-edit',
            //delbtn: '.btn-del',
            //multibtn: '.btn-multi',
            //restoreonebtn: '.btn-restoreone',
            //destroyonebtn: '.btn-destroyone',
            //restoreallbtn: '.btn-restoreall',
            //destroyallbtn: '.btn-destroyall',
            disabledbtn: '.btn-disabled',
        },
        api: {
            bindevent: function(tableId) {
                var tableId = tableId || Table.init.table_render_id;
                var options = layui.table.getOptions(tableId);
                options.layFilter = options.layFilter || options.id;

                //监听表格复选框选择
                Table.listenCheckboxEvent(options);
                //监听顶部工具栏
                Table.listenToolbarEvent(options);
                //监听单元格元素事件（编辑和删除）
                Table.listenToolEvent(options);
                // 监听表格文本框编辑
                Table.listenEditEvent(options, tableId);
                // 监听表格开关切换
                Table.listenSwitch(options, tableId);
            },
            // 批量操作请求
            multi: function(action, ids, tableId, element) {
                var options = layui.table.getOptions(tableId);
                var data = element ? $(element).data() : {};
                ids = $.isArray(ids) ? ids : [ids];
                var url = typeof data.url !== "undefined" ? data.url : (action == "del" ? options.init.delete_url : options.init.multi_url);
                var params = typeof data.params !== "undefined" ? (typeof data.params == 'object' ? $.param(data.params) : data.params) : '';
                options = { url: url, data: { action: action, id: ids, param: params } };
                Yzn.api.ajax(options, function(data, ret) {
                    var success = $(element).data("success") || $.noop;
                    if (typeof success === 'function') {
                        if (false === success.call(element, data, ret)) {
                            return false;
                        }
                    }
                    tableId && table.reload(tableId);
                }, function(data, ret) {
                    var error = $(element).data("error") || $.noop;
                    if (typeof error === 'function') {
                        if (false === error.call(element, data, ret)) {
                            return false;
                        }
                    }
                });
            },
            // 获取选中的条目ID集合
            selectedids: function(tableId, current) {
                var checkStatus = table.checkStatus(tableId),
                    data = checkStatus.data;
                var ids = [];
                $.each(data, function(i, v) {
                    ids.push(v.id);
                });
                return ids;
            },
            // 获取选中的数据
            selecteddata: function(tableId, current) {
                var checkStatus = table.checkStatus(tableId),
                    data = checkStatus.data;
                var arr = [];
                $.each(data, function(i, v) {
                    arr.push(v);
                });
                return arr;
            },
            //替换URL中的数据
            toolSpliceUrl: function(url, data) {
                data.id = typeof data.id !== 'undefined' ? data.id : 0;
                url = url == null || url.length === 0 ? '' : url.toString();
                //自动添加id参数
                url = !url.match(/(?=([?&]id=)|(\/id\/)|(\{id}))/i) ?
                    url + (url.match(/(\?|&)+/) ? "&id=" : "?id=") + '{id}' : url;
                url = url.replace(/\{(.*?)\}/gi, function(matched) {
                    matched = matched.substring(1, matched.length - 1);
                    if (matched.indexOf(".") !== -1) {
                        var temp = data;
                        var arr = matched.split(/\./);
                        for (var i = 0; i < arr.length; i++) {
                            if (typeof temp[arr[i]] !== 'undefined') {
                                temp = temp[arr[i]];
                            }
                        }
                        return typeof temp === 'object' ? '' : temp;
                    }
                    return data[matched];
                });
                return url;
            },
        },
        render: function(options) {
            options.init = options.init || init;
            options.modifyReload = Yzn.api.parame(options.modifyReload, false);
            options.id = options.id || options.init.table_render_id;
            options.pk = options.pk || 'id';
            options.elem = options.elem || options.init.table_elem;
            options.cols = options.cols || [];
            options.layFilter = options.id + '_LayFilter';
            options.url = Yzn.api.fixurl(options.url || options.init.index_url);
            options.search = Yzn.api.parame(options.search, true);
            options.showSearch = Yzn.api.parame(options.showSearch, true);
            options.searchFormVisible = Yzn.api.parame(options.searchFormVisible, false);
            options.searchFormTpl = Yzn.api.parame(options.searchFormTpl || options.init.searchFormTpl, false);
            options.defaultToolbar = options.defaultToolbar || ['filter', 'print', 'exports'];
            if (options.search && options.showSearch) {
                options.defaultToolbar.push({
                    title: '搜索',
                    layEvent: 'TABLE_SEARCH',
                    icon: 'layui-icon-search',
                    extend: 'data-table-id="' + options.id + '"'
                })
            }
            options.even = Yzn.api.parame(options.even, true);
            // 判断是否为移动端
            if (Yzn.api.checkMobile()) {
                options.defaultToolbar = !options.search ? ['filter'] : ['filter', {
                    title: '搜索',
                    layEvent: 'TABLE_SEARCH',
                    icon: 'layui-icon-search',
                    extend: 'data-table-id="' + options.id + '"'
                }];
            }
            options.searchInput = options.search ? Yzn.api.parame(options.searchInput, true) : false;

            var tableDone = options.done || function() {};
            options.done = function(res, curr, count) {
                tableDone(res, curr, count);
            };

            // 判断元素对象是否有嵌套的
            options.cols = Table.formatCols(options.cols, options.init);

            // 初始化表格lay-filter
            $(options.elem).attr('lay-filter', options.layFilter);

            //自定义搜索
            if (options.search === true && options.searchFormTpl !== false) {
                data = options.tpldata || {}
                laytpl($('#' + options.searchFormTpl).html()).render(data, function(html) {
                    $(options.elem).before(html);
                    Table.listenTableSearch(options.id);
                })
                // 初始化form表单
                form.render();
            }

            // 初始化表格搜索
            if (options.search === true && options.searchFormTpl === false) {
                Table.renderSearch(options.cols, options.elem, options.id, options.searchFormVisible);
            }

            // 初始化表格左上方工具栏
            options.toolbar = options.toolbar || ['refresh', 'add', 'delete', 'export'];
            options.toolbar = Table.renderToolbar(options);

            options.done = options.done ?
                (function(done) {
                    return function() {
                        done.apply(this, arguments);
                        Table.done.apply(this, arguments);
                    };
                })(options.done) :
                Table.done;


            var newTable = table.render(options);
            return newTable;
        },
        renderToolbar: function(options) {
            var d = options.toolbar,
                tableId = options.id,
                searchInput = options.searchInput,
                elem = options.elem,
                init = options.init;
            d = d || [];
            var toolbarHtml = '';
            $.each(d, function(i, v) {
                if (v === 'refresh') {
                    toolbarHtml += '<a lay-event="btn-refresh" href="javascript:;" class="layui-btn layui-btn-sm yzn-btn-primary btn-refresh" data-table-refresh="' + tableId + '"><i class="iconfont icon-loop-left-line"></i> </a>\n';
                } else if (v === 'add') {
                    if (Yzn.api.checkAuth('add', elem)) {
                        toolbarHtml += '<a lay-event="btn-add" href="javascript:;" class="layui-btn layui-btn-normal layui-btn-sm"><i class="iconfont icon-add-fill"></i> 添加</a>\n';
                    }
                } else if (v === 'edit') {
                    if (Yzn.api.checkAuth('edit', elem)) {
                        toolbarHtml += '<a lay-event="btn-edit" href="javascript:;" class="layui-btn layui-btn-normal layui-btn-sm layui-btn-disabled btn-disabled" data-table="' + tableId + '"><i class="iconfont icon-edit-2-line"></i> 编辑</a>\n';
                    }
                } else if (v === 'delete') {
                    if (Yzn.api.checkAuth('delete', elem)) {
                        toolbarHtml += '<a lay-event="btn-delete" href="javascript:;" class="layui-btn layui-btn-sm layui-btn-danger layui-btn-disabled btn-disabled" data-href="' + init.delete_url + '" data-table="' + tableId + '"><i class="iconfont icon-delete-bin-line"></i> 删除</a>\n';
                    }
                } else if (v === 'recyclebin') {
                    if (Yzn.api.checkAuth('recyclebin', elem)) {
                        toolbarHtml += '<a class="layui-btn layui-btn-warm layui-btn-sm btn-dialog" href="' + init.recyclebin_url + '" data-title="回收站"><i class="iconfont icon-recycle-line"></i> 回收站</a>\n';
                    }
                } else if (v === 'restore') {
                    if (Yzn.api.checkAuth('restore', elem)) {
                        toolbarHtml += '<a lay-event="btn-multi" class="layui-btn layui-btn-sm confirm layui-btn-disabled btn-disabled" href="javascript:;" data-url="' + init.restore_url + '" data-action="restore" data-table="' + tableId + '"><i class="iconfont icon-arrow-go-back-line"></i> 还原</a>\n';
                    }
                } else if (v === 'destroy') {
                    if (Yzn.api.checkAuth('destroy', elem)) {
                        toolbarHtml += '<a lay-event="btn-multi" class="layui-btn layui-btn-sm confirm layui-btn-danger layui-btn-disabled btn-disabled" href="javascript:;" data-url="' + init.destroy_url + '" data-action="destroy" data-table="' + tableId + '"><i class="iconfont icon-close-fill"></i> 销毁</a>\n';
                    }
                } else if (typeof v === "object") {
                    $.each(v, function(ii, vv) {
                        if (Yzn.api.checkAuth(vv.auth, elem)) {
                            toolbarHtml += Table.buildToolbarHtml(vv);
                        }
                    });
                }
            });
            if (searchInput) {
                toolbarHtml += '<input id="layui-input-search" value="" placeholder="搜索" class="layui-input layui-hide-xs" style="display:inline-block;width:auto;float: right;\n' + 'margin:2px 25px 0 0;height:28px;">\n'
            }
            return '<div>' + toolbarHtml + '</div>';
        },
        buildToolbarHtml: function(j) {
            j.html = j.html || '';
            if (j.html !== '') {
                return j.html;
            }

            var hidden, html, url, classname, refresh, extend, text, title, icon;
            hidden = typeof j.hidden === 'function' ? j.hidden.call(Table, j) : (typeof j.hidden !== 'undefined' ? j.hidden : false);
            if (hidden) {
                return '';
            }
            text = j.text ? j.text : '';
            title = j.title ? j.title : text;
            icon = j.icon ? j.icon : '';

            classname = j.class ? j.class : '';
            refresh = j.refresh ? 'data-refresh="' + j.refresh + '"' : '';
            url = j.url ? j.url : '';
            url = url ? Yzn.api.fixurl(j.url) : 'javascript:;';
            extend = typeof j.extend !== 'undefined' ? j.extend : '';

            html = '<a href="' + url + '" class="' + classname + '" ' + (refresh ? refresh + ' ' : '') + extend + ' title="' + title + '" data-table="' + Table.init.table_render_id + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>\n';
            return html;
        },
        renderSearch: function(cols, elem, tableId, searchFormVisible) {
            // TODO 只初始化第一个table搜索字段，如果存在多个(绝少数需求)，得自己去扩展
            cols = cols[0] || {};
            var newCols = [];
            var formHtml = '';
            $.each(cols, function(i, d) {
                d.field = d.field || false;
                d.fieldAlias = Yzn.api.parame(d.fieldAlias, d.field);
                d.title = d.title || d.field || '';
                d.selectList = d.selectList || {};
                d.search = Yzn.api.parame(d.search, true);
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
                                '<input type="hidden" class="operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
                                '<input class="' + d.addClass + '" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '" value="' + d.searchValue + '" placeholder="' + d.searchTip + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'select':
                            //d.searchOp = '=';
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
                                '<input type="hidden" class="operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
                                '<select class="layui-select" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '" ' + d.extend + '>\n' +
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
                                '<input type="hidden" class="operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
                                '<input class="datetime ' + d.addClass + '" data-date-range="-" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '" value="' + d.searchValue + '" placeholder="' + d.searchTip + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'time':
                            d.searchOp = '=';
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input class="datetime ' + d.addClass + '" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '"  data-search-op="' + d.searchOp + '"  value="' + d.searchValue + '" placeholder="' + d.searchTip + '" ' + d.extend + '>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'between':
                            d.searchOp = 'BETWEEN';
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<input type="hidden" class="operate" name="' + d.fieldAlias + '-operate" data-name="' + d.fieldAlias + '" value="' + d.searchOp + '" readonly>\n' +
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
                    //newCols.push(d);
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

                Table.listenTableSearch(tableId);

                // 初始化form表单
                form.render();
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
                    if (val.templet === Table.formatter.tool && val.operat === undefined) {
                        cols[i][index]['operat'] = ['edit', 'delete'];
                    }

                    // 判断是否包含开关组件
                    if (val.templet === Table.formatter.switch && val.filter === undefined) {
                        cols[i][index]['filter'] = 'switchStatus';
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
                    if (val.templet === Table.formatter.image && val.imageHeight === undefined) {
                        cols[i][index]['imageHeight'] = 30;
                    }

                    // 判断是否多层对象
                    if (val.field !== undefined && val.field.split(".").length > 1) {
                        if (val.templet === undefined) {
                            cols[i][index]['templet'] = Table.formatter.value;
                        }
                    }
                    // 判断是否列表数据转换
                    /*if (val.selectList !== undefined && val.templet === undefined) {
                        cols[i][index]['templet'] = Table.formatter.list;
                    }*/
                }
            }
            return cols;
        },
        listenTableSearch: function(tableId) {
            var that = this;
            that.$commonsearch = $(".table-search-fieldset");

            require(['form'], function(Form) {
                Form.api.bindevent(that.$commonsearch);
            })

            form.on('submit(' + tableId + '_filter)', function(data) {
                var searchQuery = Table.getSearchQuery(that, true);
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
            //表格点击搜索
            $(document).on("click", ".searchit", function() {
                var value = $(this).data("value");
                var field = $(this).data("field");
                var obj = $("form [name='" + field + "']", that.$commonsearch);
                if (obj.length > 0) {
                    if (obj.is("select")) {
                        $("option[value='" + value + "']", obj).prop("selected", true);
                        form.render('select');
                    } else if (obj.length > 1) {
                        $("form [name='" + field + "'][value='" + value + "']", that.$commonsearch).prop("checked", true);
                    } else {
                        obj.val(value + "");
                    }
                    obj.trigger("change");
                    $("form button[type='submit']", that.$commonsearch).trigger("click");
                }
            });
            //快速搜索
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
            $("form.form-commonsearch .operate", that.$commonsearch).each(function(i) {
                var name = $(this).data("name");
                var sym = $(this).is("select") ? $("option:selected", this).val() : $(this).val().toUpperCase();
                var obj = $("[name='" + name + "']", that.$commonsearch);
                if (obj.length == 0)
                    return true;
                var vObjCol = ColumnsForSearch[i];
                var process = vObjCol && typeof vObjCol.process == 'function' ? vObjCol.process : null;
                if (obj.length > 1) {
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
                        if ($("[name='" + name + "']:first", that.$commonsearch).hasClass("datetimepicker")) {
                            sym = 'RANGE';
                        }
                    } else {
                        value = $("[name='" + name + "']:checked", that.$commonsearch).val();
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
        listenCheckboxEvent: function(options) {
            table.on('checkbox(' + options.layFilter + ')', function(obj) {
                //监听表格是否选中，多选按钮禁用恢复
                var checkStatus = table.checkStatus(obj.config.id);
                $(Table.config.disabledbtn + '[data-table="' + options.id + '"]').toggleClass('layui-btn-disabled', !checkStatus.data.length);
                return false;
            })
        },
        listenToolEvent: function(options) {
            table.on('tool(' + options.layFilter + ')', function(obj) {
                var attrEvent = obj.event;
                if (Table.toolEvents.hasOwnProperty(attrEvent)) {
                    Table.toolEvents[attrEvent] && Table.toolEvents[attrEvent].call(this, obj, options);
                }
                return false;
            })
        },
        listenToolbarEvent: function(options) {
            table.on('toolbar(' + options.layFilter + ')', function(obj) {
                var attrEvent = obj.event;
                if (Table.toolbarEvents.hasOwnProperty(attrEvent)) {
                    Table.toolbarEvents[attrEvent] && Table.toolbarEvents[attrEvent].call(this, obj, options);
                }
                return false;
            })
        },
        listenEditEvent: function(options, tableId) {
            table.on('edit(' + options.layFilter + ')', function(obj) {
                Table.editEvents['edit'].call(this, obj, options)
                return false;
            })
        },
        listenSwitch: function(option, tableId) {
            var modifyReload = option.modifyReload || false;
            layui.form.on('switch(switchStatus)', function(obj) {
                var that = $(this);
                var url = $(this).attr('data-url') || option.init.multi_url;
                var field = $(this).attr('data-field') || 'status';
                var data = {
                    id: obj.value,
                    param: field + '=' + (obj.elem.checked ? 1 : 0),
                };
                Yzn.api.ajax({
                    url: url,
                    data: data,
                }, function(data, ret) {
                    if (modifyReload) {
                        layui.table.reload(tableId);
                    }
                }, function(data, ret) {
                    that.trigger('click');
                    layui.form.render('checkbox');
                });
            });
        },
        buildOperatHtml: function(row, j) {
            var hidden, disable, url, classname, icon, text, title, refresh, confirm, extend,
                dropdown, html;
            hidden = typeof j.hidden === 'function' ? j.hidden.call(Table, row, j) : (typeof j.hidden !== 'undefined' ? j.hidden : false);
            if (hidden) {
                return '';
            }
            url = j.url ? j.url : '';
            url = typeof url === 'function' ? url.call(Table, row, j) : (url ? Yzn.api.fixurl(Table.api.toolSpliceUrl(url, row)) : 'javascript:;');
            classname = j.class ? j.class : '';
            icon = j.icon ? j.icon : '';
            text = typeof j.text === 'function' ? j.text.call(Table, row, j) : j.text ? j.text : '';
            title = typeof j.title === 'function' ? j.title.call(Table, row, j) : j.title ? j.title : text;
            refresh = j.refresh ? 'data-refresh="' + j.refresh + '"' : '';
            confirm = typeof j.confirm === 'function' ? j.confirm.call(Table, row, j) : (typeof j.confirm !== 'undefined' ? j.confirm : false);
            confirm = confirm ? 'data-confirm="' + confirm + '"' : '';
            extend = typeof j.extend === 'function' ? j.extend.call(Table, row, j) : (typeof j.extend !== 'undefined' ? j.extend : '');
            disable = typeof j.disable === 'function' ? j.disable.call(Table, row, j) : (typeof j.disable !== 'undefined' ? j.disable : false);
            if (disable) {
                classname = classname + ' disabled';
            }
            html = '<a href="' + url + '" class="' + classname + '" ' + (confirm ? confirm + ' ' : '') + (refresh ? refresh + ' ' : '') + extend + ' title="' + title + '" data-table="' + Table.init.table_render_id + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>';
            return html;
        },
        getItemField: function(item, field) {
            var value = item;

            if (typeof field !== 'string' || item.hasOwnProperty(field)) {
                return item[field];
            }
            var props = field.split('.');
            for (var p in props) {
                if (props.hasOwnProperty(p)) {
                    value = value && value[props[p]];
                }
            }
            return value;
        },
        done: function (res, curr, count) {
            //初始化导入按钮
            const btnImport = $('[lay-event="btn-import"]');
            if (btnImport.length > 0) {
                btnImport.click();
            }
        },
        toolbarEvents: {
            //监听头部工具栏-刷新
            'btn-refresh': function(obj, options) {
                // 监听动态表格刷新
                tableId = options.init.table_render_id || $(this).attr('data-table-refresh');
                table.reload(tableId);
            },
            //监听头部工具栏-新增
            'btn-add': function(obj, options) {
                // 监听弹出层的打开
                var url = options.init.add_url;
                Yzn.api.open(url, $(this).data("original-title") || $(this).attr("title") || '添加', $(this).data() || {});
            },
            //监听头部工具栏-编辑
            'btn-edit': function(obj, options) {
                // 批量编辑按钮事件
                tableId = options.init.table_render_id;
                var that = this;
                var ids = Table.api.selectedids(tableId);
                if (ids.length <= 0) {
                    Toastr.error('请勾选需要操作的数据');
                    return false;
                }
                if (ids.length > 10) {
                    Toastr.error('最多勾选10条数据');
                    return false;
                }
                var title = $(that).data('title') || $(that).attr("title") || '编辑';
                var data = $(that).data() || {};
                delete data.title;
                //循环弹出多个编辑框
                $.each(Table.api.selecteddata(tableId), function(index, row) {
                    var url = options.init.edit_url;
                    row = $.extend({}, row ? row : {}, { id: row[options.pk] });
                    url = Table.api.toolSpliceUrl(url, row);
                    Yzn.api.open(url, typeof title === 'function' ? title.call(table, row) : title, data);
                });
            },
            //监听头部工具栏-删除
            'btn-delete': function(obj, options) {
                //表格批量删除
                tableId = options.init.table_render_id;
                var that = this;
                var ids = Table.api.selectedids(tableId);
                if (ids.length <= 0) {
                    Toastr.error('请勾选需要操作的数据');
                    return false;
                }
                Layer.confirm('删除之后无法恢复，您确定要删除吗？', { icon: 3, title: '提示信息', offset: 0, shadeClose: true, btn: ['确认', '取消'] },
                    function(index) {
                        Table.api.multi("del", ids, tableId, that);
                        Layer.close(index);
                    }
                );
            },
            //监听头部工具栏-多选操作
            'btn-multi': function(obj, options) {
                // 批量操作按钮事件
                tableId = options.init.table_render_id;
                var ids = Table.api.selectedids(tableId);
                if (ids.length <= 0) {
                    Toastr.error('请勾选需要操作的数据');
                    return false;
                }
                Table.api.multi($(this).data("action"), ids, tableId, this);
            },
            //监听头部工具栏-批量导入
            'btn-import': function(obj, options) {
                var that = this;
                require(['upload'], function (Upload) {
                    var tableId = options.init.table_render_id || $(this).attr('data-table-refresh');
                    Upload.api.upload($(that), function (data, ret) {
                        Yzn.api.ajax({
                            url: options.init.import_url,
                            data: {file: data.url},
                        }, function (data, ret) {
                           table.reload(tableId);
                        });
                    });
                });
            },
            //监听头部工具栏-搜索点击
            'TABLE_SEARCH': function(obj, options) {
                // 搜索表单的显示
                tableId = options.init.table_render_id;
                var searchFieldsetId = 'searchFieldset_' + tableId;
                var _that = $("#" + searchFieldsetId);
                if (_that.hasClass("layui-hide")) {
                    _that.removeClass('layui-hide');
                } else {
                    _that.addClass('layui-hide');
                }
            }
        },
        toolEvents: {
            //监听表格右侧工具-编辑
            'btn-editone': function(obj, options) {
                var data = obj.data;
                var ids = data[options.pk];
                var row = $.extend({}, data ? data : {}, { id: ids });
                var url = options.init.edit_url;
                Yzn.api.open(Table.api.toolSpliceUrl(url, row), $(this).data("original-title") || $(this).attr("title") || '编辑', $(this).data() || {});
            },
            //监听表格右侧工具-删除
            'btn-delone': function(obj, options) {
                var tableId = options.init.table_render_id;
                var data = obj.data;
                var top = $(this).offset().top - $(window).scrollTop();
                var left = $(this).offset().left - $(window).scrollLeft() - 260;
                if (top + 154 > $(window).height()) {
                    top = top - 154;
                }
                if ($(window).width() < 480) {
                    top = left = undefined;
                }
                Layer.confirm('删除之后无法恢复，您确定要删除吗？', { icon: 3, title: '提示信息', offset: [top, left], shadeClose: true, btn: ['确认', '取消'] },
                    function(index) {
                        Table.api.multi("del", data[options.pk], tableId, this);
                        Layer.close(index);
                    }
                );
            }
        },
        editEvents: {
            //监听表格文本框-编辑
            'edit': function(obj, options) {
                var tableId = options.init.table_render_id;
                var url = options.init.multi_url;
                var modifyReload = options.modifyReload || false;

                var data = {
                    id: obj.data.id,
                    param: obj.field + '=' + obj.value,
                };
                Yzn.api.ajax({
                    url: url,
                    data: data,
                }, function(data, ret) {
                    if (modifyReload) {
                        layui.table.reload(tableId);
                    }
                });
            }
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
                                    field: 'id',
                                    icon: 'iconfont icon-edit-2-line',
                                    auth: 'edit',
                                    text: "",
                                    title: '编辑信息',
                                    extend: "lay-event='btn-editone'"
                                };
                                if (Yzn.api.checkAuth(operat.auth, elem)) {
                                    html += Table.buildOperatHtml(data, operat);
                                }
                                break;
                            case 'restore':
                                var operat = {
                                    class: 'layui-btn layui-btn-xs btn-ajax',
                                    field: 'id',
                                    icon: 'iconfont icon-arrow-go-back-line',
                                    auth: 'restore',
                                    text: "还原",
                                    title: '还原',
                                    url: that.init.restore_url,
                                    refresh: true,
                                    extend: ""
                                };
                                if (Yzn.api.checkAuth(operat.auth, elem)) {
                                    html += Table.buildOperatHtml(data, operat);
                                }
                                break;
                            case 'destroy':
                                var operat = {
                                    class: 'layui-btn layui-btn-danger layui-btn-xs btn-ajax',
                                    field: 'id',
                                    icon: 'iconfont icon-close-fill',
                                    auth: 'destroy',
                                    text: "销毁",
                                    title: '销毁',
                                    url: that.init.destroy_url,
                                    refresh: true,
                                    extend: ""
                                };
                                if (Yzn.api.checkAuth(operat.auth, elem)) {
                                    html += Table.buildOperatHtml(data, operat);
                                }
                                break;
                            case 'delete':
                                var operat = {
                                    class: 'layui-btn layui-btn-danger layui-btn-xs',
                                    field: 'id',
                                    icon: 'iconfont icon-delete-bin-line',
                                    auth: 'delete',
                                    text: "",
                                    title: '删除',
                                    extend: "lay-event='btn-delone'"
                                };
                                if (Yzn.api.checkAuth(operat.auth, elem)) {
                                    html += Table.buildOperatHtml(data, operat);
                                }
                                break;
                        }

                    } else if (typeof item === 'object') {
                        $.each(item, function(i, operat) {
                            if (Yzn.api.checkAuth(operat.auth, elem)) {
                                html += Table.buildOperatHtml(data, operat);
                            }
                        });
                    }
                });
                return html;
            },
            status: function(data) {
                var custom = { normal: 'success', hidden: 'gray', deleted: 'danger', locked: 'info' };
                if (typeof this.custom !== 'undefined') {
                    custom = $.extend(custom, this.custom);
                }
                this.custom = custom;
                this.icon = 'iconfont icon-circle-fill';
                return Table.formatter.normal.call(this, data);
            },
            normal: function(data) {
                var that = this;
                var colorArr = ["danger", "success", "primary", "warning", "info", "gray", "red", "yellow", "aqua", "blue", "navy", "teal", "olive", "lime", "fuchsia", "purple", "maroon"];
                var custom = {};
                if (typeof that.custom !== 'undefined') {
                    custom = $.extend(custom, that.custom);
                }
                var field = that.field;
                try {
                    var value = Table.getItemField(data, field);
                    value = value == null || value.length === 0 ? '' : value.toString();
                } catch (e) {
                    var value = undefined;
                }
                value = value == null || value.length === 0 ? '' : value.toString();
                var keys = typeof that.selectList === 'object' ? Object.keys(that.selectList) : [];
                var index = keys.indexOf(value);
                var color = value && typeof custom[value] !== 'undefined' ? custom[value] : null;
                var display = index > -1 ? that.selectList[value] : null;
                var icon = typeof that.icon !== 'undefined' ? that.icon : null;
                if (!color) {
                    color = index > -1 && typeof colorArr[index] !== 'undefined' ? colorArr[index] : 'primary';
                }
                if (!display) {
                    display = value.charAt(0).toUpperCase() + value.slice(1);
                }
                var html = '<span class="text-' + color + '">' + (icon ? '<i class="' + icon + '"></i>' : '') + display + '</span>';
                if (that.search != false) {
                    html = '<a href="javascript:;" class="searchit" lay-tips="点击搜索 ' + display + '" data-field="' + this.field + '" data-value="' + value + '">' + html + '</a>';
                }
                return html;
            },
            flag: function(data) {
                var that = this;
                var field = that.field;
                try {
                    var value = Table.getItemField(data, field);
                    value = value == null || value.length === 0 ? '' : value.toString();
                } catch (e) {
                    var value = undefined;
                }
                //赤色 墨绿 蓝色 藏青 雅黑 橙色
                var colorArr = { 0: 'red', 1: 'green', 2: 'blue', 3: 'cyan', 4: 'black', 5: 'orange' };
                //如果字段列有定义custom
                if (typeof that.custom !== 'undefined') {
                    colorArr = $.extend(colorArr, that.custom);
                }
                if (typeof that.selectList === 'object' && typeof that.custom === 'undefined') {
                    var i = 0;
                    var searchValues = Object.values(colorArr);
                    $.each(that.selectList, function(key, val) {
                        if (typeof colorArr[key] == 'undefined') {
                            colorArr[key] = searchValues[i];
                            i = typeof searchValues[i + 1] === 'undefined' ? 0 : i + 1;
                        }
                    });
                }

                //渲染Flag
                var html = [];
                var arr = value != '' ? value.split(',') : [];
                var color, display, label;
                $.each(arr, function(i, value) {
                    value = value == null || value.length === 0 ? '' : value.toString();
                    if (value == '')
                        return true;
                    color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'green';
                    display = typeof that.selectList !== 'undefined' && typeof that.selectList[value] !== 'undefined' ? that.selectList[value] : value.charAt(0).toUpperCase() + value.slice(1);
                    label = '<span class="layui-badge layui-bg-' + color + '">' + display + '</span>';
                    if (that.search != false) {
                        html.push('<a href="javascript:;" class="searchit" lay-tips="点击搜索 ' + display + '" data-field="' + field + '" data-value="' + value + '">' + label + '</a>');
                    } else {
                        html.push(label);
                    }
                })
                return html.join(' ');
            },
            label: function(data) {
                return Table.formatter.flag.call(this, data);
            },
            switch: function(data) {
                var that = this;
                var field = that.field;
                that.filter = that.filter || that.field || null;
                that.checked = that.checked || 1;
                that.tips = that.tips || '开|关';
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                var checked = value === that.checked ? 'checked' : '';
                return laytpl('<input type="checkbox" name="' + that.field + '" value="' + data.id + '" lay-skin="switch" lay-text="' + that.tips + '" lay-filter="' + that.filter + '" ' + checked + ' >').render(data);
            },
            image: function(data) {
                var that = this;
                that.imageWidth = that.imageWidth || 30;
                that.imageHeight = that.imageHeight || 30;
                that.imageSplit = that.imageSplit || ',';
                that.imageJoin = that.imageJoin || ' ';
                that.title = that.title || that.field;
                var field = that.field,
                    title = data[that.title];
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                if (!value) {
                    return '';
                } else {
                    var valuesHtml = [];
                    valuesHtml.push('<img style="width: ' + that.imageWidth + 'px; height: ' + that.imageHeight + 'px;" src="' + value + '" data-image="' + title + '">');

                    return valuesHtml.join(that.imageJoin);
                }
            },
            images: function(data) {
                var that = this;
                that.imageWidth = that.imageWidth || 30;
                that.imageHeight = that.imageHeight || 30;
                that.imageSplit = that.imageSplit || ',';
                that.imageJoin = that.imageJoin || ' ';
                that.title = that.title || that.field;
                var field = that.field,
                    title = data[that.title];
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                if (!value) {
                    return '';
                } else {
                    var values = value.split(that.imageSplit),
                        valuesHtml = [];
                    values.forEach(function(value, index) {
                        valuesHtml.push('<img style="width: ' + that.imageWidth + 'px; height: ' + that.imageHeight + 'px;" src="' + value + '" data-image="' + title + '">');
                    });
                    return valuesHtml.join(that.imageJoin);
                }
            },
            file: function(data) {
                var that = this;
                that.imageWidth = that.imageWidth || 80;
                that.imageHeight = that.imageHeight || 30;
                that.imageSplit = that.imageSplit || ',';
                that.imageJoin = that.imageJoin || ' ';
                that.title = that.title || that.field;
                var field = that.field,
                    title = data[that.title];
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                if (!value) {
                    return '';
                } else {
                    var values = value.split(that.imageSplit),
                        valuesHtml = [];
                    values.forEach(function(value, index) {
                        suffix = /[\.]?([a-zA-Z0-9]+)$/.exec(value);
                        suffix = suffix ? suffix[1] : 'file';
                        url = Yzn.api.fixurl("ajax/icon?suffix=" + suffix);
                        valuesHtml.push('<img style="max-width: ' + that.imageWidth + 'px; max-height: ' + that.imageHeight + 'px;" src="' + url + '" data-image="' + title + '">');
                    });
                    return valuesHtml.join(that.imageJoin);
                }
            },
            files: function(data) {
                return Table.formatter.image.file(this, data);
            },
            url: function(data) {
                var field = this.field;
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                return '<a class="layui-btn layui-btn-primary layui-btn-xs" href="' + value + '" target="_blank"><i class="iconfont icon-lianjie"></i></a></a>';
            },
            price: function(data) {
                var field = this.field;
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                return '<span>￥' + value + '</span>';
            },
            icon: function(data) {
                var field = this.field;
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                return '<i class="' + value + '"></i>';
            },
            text: function(data) {
                var field = this.field;
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                return '<span class="line-limit-length">' + value + '</span>';
            },
            value: function(data) {
                var field = this.field;
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                return '<span>' + value + '</span>';
            },
            datetime: function(data) {
                var that = this;
                var field = that.field;
                try {
                    var value = Table.getItemField(data, field);
                } catch (e) {
                    var value = undefined;
                }
                var datetimeFormat = typeof that.datetimeFormat === 'undefined' ? 'yyyy-MM-dd HH:mm:ss' : that.datetimeFormat;
                if (value && isNaN(Date.parse(value))) {
                    return layui.util.toDateString(value * 1000, datetimeFormat)
                } else if (value && !isNaN(Date.parse(value))) {
                    return layui.util.toDateString(Date.parse(value), datetimeFormat)
                } else {
                    return '-';
                }
            }
        },
    }
    return Table;
});