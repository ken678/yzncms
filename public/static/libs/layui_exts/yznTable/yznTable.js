/**
 @ Name：简单封下table
 */
layui.define(['form', 'table', 'yzn', 'laydate', 'laytpl', 'element', 'yznForm'], function(exports) {
    var MOD_NAME = 'yznTable',
        $ = layui.$,
        table = layui.table,
        yzn = layui.yzn,
        laydate = layui.laydate,
        element = layui.element,
        laytpl = layui.laytpl,
        form = layui.form;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTable',
    };

    yznTable = {
        render: function(options) {
            options.init = options.init || init;
            options.modifyReload = yzn.parame(options.modifyReload, false);
            options.id = options.id || options.table_render_id;
            options.elem = options.elem || options.init.table_elem;
            options.cols = options.cols || [];
            options.layFilter = options.id + '_LayFilter';
            options.search = yzn.parame(options.search, true);
            options.searchFormVisible = yzn.parame(options.searchFormVisible, false);
            options.defaultToolbar = (options.defaultToolbar === undefined && !options.search) ? ['filter', 'print', 'exports'] : ['filter', 'print', 'exports', {
                title: '搜索',
                layEvent: 'TABLE_SEARCH',
                icon: 'layui-icon-search',
                extend: 'data-table-id="' + options.id + '"'
            }];
            options.searchInput = options.search ? yzn.parame(options.searchInput || options.init.searchInput, true):false;

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
            //options.toolbar = yznTable.renderToolbar(options.toolbar, options.elem, options.id, options.init);
            options.toolbar = yznTable.renderToolbar(options);

            var newTable = table.render(options);
            // 监听表格搜索开关显示
            yznTable.listenToolbar(options.layFilter, options.id);
            // 监听表格开关切换
            //yznTable.renderSwitch(options.cols, options.init, options.id, options.modifyReload);
            // 监听表格文本框编辑
            yznTable.listenEdit(options.init, options.layFilter, options.id, options.modifyReload);
            return newTable;
        },
        renderToolbar: function(options) {
            var d = options.toolbar, tableId = options.id, searchInput = options.searchInput,init = options.init;
            d = d || [];
            var toolbarHtml = '';
            $.each(d, function (i, v) {
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

            if(toolbar.html !== ''){
                return toolbar.html;
            }

            var formatToolbar = toolbar;
            formatToolbar.icon = formatToolbar.icon !== '' ? '<i class="' + formatToolbar.icon + '"></i> ' : '';
            formatToolbar.class = formatToolbar.class !== '' ? 'class="' + formatToolbar.class + '" ' : '';

            if (toolbar.method === 'open') {
                formatToolbar.method = formatToolbar.method !== '' ? 'data-open="' + formatToolbar.url + '" data-title="' + formatToolbar.title + '" ' : '';
            }else{
                formatToolbar.method = formatToolbar.method !== '' ? 'data-request="' + formatToolbar.url + '" data-title="' + formatToolbar.title + '" ' : '';
            }

            formatToolbar.checkbox = toolbar.checkbox ? ' data-checkbox="true" ' : '';
            formatToolbar.tableId = tableId !== undefined ? ' data-table="' + tableId + '" ' : '';

            html = '<button ' + formatToolbar.class + formatToolbar.method + formatToolbar.extend + formatToolbar.checkbox + formatToolbar.tableId + '>' + formatToolbar.icon + formatToolbar.text + '</button>\n';
            return html;
        },
        renderSearch: function(cols, elem, tableId,searchFormVisible) {
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
                d.searchOp = d.searchOp || '%*%';
                d.timeType = d.timeType || 'datetime';
                var extend = typeof d.extend === 'undefined' ? '' : d.extend;
                var addClass = typeof d.addClass === 'undefined' ? (typeof d.addclass === 'undefined' ? 'layui-input' : 'layui-input ' + d.addclass) : 'layui-input ' + d.addClass;
                if (d.field !== false && d.search !== false) {
                    switch (d.search) {
                        case true:
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input class="'+ addClass +'" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '" data-search-op="' + d.searchOp + '" value="' + d.searchValue + '" placeholder="' + d.searchTip + '" '+extend+'>\n' +
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
                                '<select class="layui-select" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '"  data-search-op="' + d.searchOp + '" >\n' +
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
                                '<input class="'+ addClass +'" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '"  data-search-op="' + d.searchOp + '"  value="' + d.searchValue + '" placeholder="' + d.searchTip + '" '+extend+'>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'time':
                            d.searchOp = '=';
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input class="'+ addClass +'" id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '"  data-search-op="' + d.searchOp + '"  value="' + d.searchValue + '" placeholder="' + d.searchTip + '" '+extend+'>\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                    }
                    newCols.push(d);
                }
            });
            if (formHtml !== '') {
                $(elem).before('<fieldset style="border:1px solid #ddd;" id="searchFieldset_' + tableId + '" class="table-search-fieldset '+ (searchFormVisible ? "" : "layui-hide")+'">\n' +
                    '<legend>条件搜索</legend>\n' +
                    '<form class="layui-form layui-form-pane form-search">\n' +
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
                    if (val.templet === yznTable.tool && val.operat === undefined) {
                        cols[i][index]['operat'] = ['edit', 'delete'];
                    }

                    // 判断是否包含开关组件
                    if (val.templet === yznTable.switch && val.filter === undefined) {
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
                    if (val.templet === yznTable.image && val.imageHeight === undefined) {
                        cols[i][index]['imageHeight'] = 30;
                    }

                    // 判断是否多层对象
                    if (val.field !== undefined && val.field.split(".").length > 1) {
                        if (val.templet === undefined) {
                            cols[i][index]['templet'] = yznTable.value;
                        }
                    }

                    // 判断是否列表数据转换
                    if (val.selectList !== undefined && val.templet === undefined) {
                        cols[i][index]['templet'] = yznTable.list;
                    }

                }
            }
            return cols;
        },
        listenTableSearch: function(tableId) {
            form.on('submit(' + tableId + '_filter)', function(data) {
                var dataField = data.field;
                var formatFilter = {},
                    formatOp = {};

                $.each(dataField, function(key, val) {
                    if (val !== '') {
                        formatFilter[key] = val;
                        var op = $('#c-' + key).attr('data-search-op');
                        op = op || '%*%';
                        formatOp[key] = op;
                    }
                });
                table.reload(tableId, {
                    page: {
                        curr: 1
                    },
                    where: {
                        filter: JSON.stringify(formatFilter),
                        op: JSON.stringify(formatOp)
                    }
                });
                return false;
            });
            $(document).on('blur', '#layui-input-search', function (event) {
                var text = $(this).val();
                table.reload(tableId, {where:{search: text}});
                $('#layui-input-search').prop("value",$(this).val());
                return false
            })
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
        listenEdit: function(tableInit, layFilter, tableId, modifyReload) {
            //console.log(tableInit.modify_url);
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
                    //console.log(_data);
                    yzn.request.post({
                        url: tableInit.modify_url,
                        prefix: true,
                        data: _data,
                    }, function(res) {
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
        buildOperatHtml: function (operat) {
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
            } else {
                formatOperat.method = formatOperat.method !== '' ? 'data-request="' + formatOperat.url + '" data-title="' + formatOperat.title + '" ' : '';
            }
            html = '<a ' + formatOperat.class + formatOperat.method + formatOperat.extend + '>' + formatOperat.icon + formatOperat.text + '</a>';
            return html;
        },
        tool: function (data, option) {
            option.operat = option.operat || ['edit', 'delete'];
            var elem = option.init.table_elem || init.table_elem;
            var html = '';
            $.each(option.operat, function (i, item) {
                if (typeof item === 'string') {
                    switch (item) {
                        case 'edit':
                            var operat = {
                                class: 'layui-btn layui-btn-success layui-btn-xs',
                                method: 'open',
                                field: 'id',
                                icon: '',
                                text: '编辑',
                                title: '编辑信息',
                                url: option.init.edit_url,
                                extend: ""
                            };
                            operat.url = yznTable.toolSpliceUrl(operat.url, operat.field, data);
                            //if (admin.checkAuth(operat.auth, elem)) {
                                html += yznTable.buildOperatHtml(operat);
                            //}
                            break;
                        case 'delete':
                            var operat = {
                                class: 'layui-btn layui-btn-danger layui-btn-xs',
                                method: 'get',
                                field: 'id',
                                icon: '',
                                text: '删除',
                                title: '确定删除？',
                                url: option.init.delete_url,
                                extend: ""
                            };
                            operat.url = yznTable.toolSpliceUrl(operat.url, operat.field, data);
                            //if (admin.checkAuth(operat.auth, elem)) {
                                html += yznTable.buildOperatHtml(operat);
                            //}
                            break;
                    }

                } else if (typeof item === 'object') {
                    $.each(item, function (i, operat) {
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
        image: function(data, option) {
            option.imageWidth = option.imageWidth || 80;
            option.imageHeight = option.imageHeight || 30;
            option.imageSplit = option.imageSplit || '|';
            option.imageJoin = option.imageJoin || '<br>';
            option.title = option.title || option.field;
            var field = option.field,
                title = data[option.title];
            try {
                var value = eval("data." + field);
            } catch (e) {
                var value = undefined;
            }
            if (!value) {
                return '';
            } else {
                var values = value.split(option.imageSplit),
                    valuesHtml = [];
                values.forEach((value, index) => {
                    valuesHtml.push('<img style="max-width: ' + option.imageWidth + 'px; max-height: ' + option.imageHeight + 'px;" src="' + value + '" data-image="' + title + '">');
                });
                return valuesHtml.join(option.imageJoin);
            }
        },
        url: function(data, option) {
            var field = option.field;
            try {
                var value = eval("data." + field);
            } catch (e) {
                var value = undefined;
            }
            return '<a class="layui-btn layui-btn-primary layui-btn-xs" href="' + value + '" target="_blank"><i class="iconfont icon-lianjie"></i></a></a>';
        },
        price: function(data, option) {
            var field = option.field;
            try {
                var value = eval("data." + field);
            } catch (e) {
                var value = undefined;
            }
            return '<span>￥' + value + '</span>';
        },
        icon: function(data, option) {
            var field = option.field;
            try {
                var value = eval("data." + field);
            } catch (e) {
                var value = undefined;
            }
            return '<i class="' + value + '"></i>';
        },
        text: function(data, option) {
            var field = option.field;
            try {
                var value = eval("data." + field);
            } catch (e) {
                var value = undefined;
            }
            return '<span class="line-limit-length">' + value + '</span>';
        },
        value: function(data, option) {
            var field = option.field;
            try {
                var value = eval("data." + field);
            } catch (e) {
                var value = undefined;
            }
            return '<span>' + value + '</span>';
        },
    }
    exports(MOD_NAME, yznTable);
});