/**
 @ Name：简单封下table
 */
layui.define(['form', 'table','yzn'], function(exports) {
    var MOD_NAME = 'yznTable',
        $ = layui.$,
        table = layui.table,
        yzn = layui.yzn,
        form = layui.form;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
    };

    yznTable = {
        parame: function(param, defaultParam) {
            return param !== undefined ? param : defaultParam;
        },
        render: function(options) {
            options.init = options.init || init;
            options.modifyReload = yznTable.parame(options.modifyReload, true);
            options.id = options.id || options.table_render_id;
            options.elem = options.elem || options.init.table_elem;
            options.cols = options.cols || [];
            options.layFilter = options.id + '_LayFilter';
            options.search = yznTable.parame(options.search, true);
            options.defaultToolbar = (options.defaultToolbar === undefined && !options.search) ? ['filter', 'print', 'exports'] : ['filter', 'print', 'exports', {
                title: '搜索',
                layEvent: 'TABLE_SEARCH',
                icon: 'layui-icon-search',
                extend: 'data-table-id="' + options.id + '"'
            }];

            // 初始化表格lay-filter
            $(options.elem).attr('lay-filter', options.layFilter);

            // 初始化表格搜索
            if (options.search === true) {
                yznTable.renderSearch(options.cols, options.elem, options.id);
            }
            var newTable = table.render(options);
            // 监听表格搜索开关显示
            yznTable.listenToolbar(options.layFilter, options.id);
            // 监听表格开关切换
            //yznTable.renderSwitch(options.cols, options.init, options.id, options.modifyReload);
            // 监听表格文本框编辑
            yznTable.listenEdit(options.init, options.layFilter, options.id, options.modifyReload);
            return newTable;
        },
        renderSearch: function(cols, elem, tableId) {
            // TODO 只初始化第一个table搜索字段，如果存在多个(绝少数需求)，得自己去扩展
            cols = cols[0] || {};
            var newCols = [];
            var formHtml = '';
            $.each(cols, function(i, d) {
                d.field = d.field || false;
                d.fieldAlias = yznTable.parame(d.fieldAlias, d.field);
                d.title = d.title || d.field || '';
                d.selectList = d.selectList || {};
                d.search = yznTable.parame(d.search, true);
                d.searchTip = d.searchTip || '请输入' + d.title || '';
                d.searchValue = d.searchValue || '';
                d.searchOp = d.searchOp || '%*%';
                d.timeType = d.timeType || 'datetime';
                if (d.field !== false && d.search !== false) {
                    switch (d.search) {
                        case true:
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '" data-search-op="' + d.searchOp + '" value="' + d.searchValue + '" placeholder="' + d.searchTip + '" class="layui-input">\n' +
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
                                '<input id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '"  data-search-op="' + d.searchOp + '"  value="' + d.searchValue + '" placeholder="' + d.searchTip + '" class="layui-input">\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                        case 'time':
                            d.searchOp = '=';
                            formHtml += '\t<div class="layui-form-item layui-inline">\n' +
                                '<label class="layui-form-label">' + d.title + '</label>\n' +
                                '<div class="layui-input-inline">\n' +
                                '<input id="c-' + d.fieldAlias + '" name="' + d.fieldAlias + '"  data-search-op="' + d.searchOp + '"  value="' + d.searchValue + '" placeholder="' + d.searchTip + '" class="layui-input">\n' +
                                '</div>\n' +
                                '</div>';
                            break;
                    }
                    newCols.push(d);
                }
            });
            if (formHtml !== '') {
                $(elem).before('<fieldset id="searchFieldset_' + tableId + '" class="table-search-fieldset layui-hide">\n' +
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
                }, 'data');
                return false;
            });
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
    }


    exports(MOD_NAME, yznTable);
});