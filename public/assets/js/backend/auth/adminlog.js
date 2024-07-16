define(['jquery', 'table', 'form','layui'], function($, Table, Form,layui) {

    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                delete_url: "auth.adminlog/deletelog",
                detail_url: "auth.adminlog/detail",
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh',
                    [{
                        text: '删除一个月前日志',
                        url: Table.init.delete_url,
                        auth: 'delete',
                        icon: 'iconfont icon-delete-bin-line',
                        class: 'layui-btn layui-btn-sm layui-btn-danger btn-ajax',
                        extend: 'data-refresh="true"',
                    }]
                ],
                url: 'auth.adminlog/index',
                cols: [
                    [
                        { field: 'id', width: 80, title: 'ID', sort: true },
                        { field: 'username', width: 120, title: '用户名' },
                        { field: 'title', title: '标题', searchOp: 'like' },
                        { field: 'url', title: '操作URL' },
                        { field: 'ip', width: 120, title: 'IP' },
                        {
                            field: 'useragent',
                            width: 120,
                            title: 'Browser',
                            templet: function(d) {
                                return '<span>' + layui.util.escape(d.useragent.split(" ")[0]) + '</span>';
                            }
                        },
                        { field: 'create_time', width: 180, title: '时间', search: 'range' },
                        {
                            width: 90,
                            title: '操作',
                            templet: Table.formatter.tool,
                            operat: [
                                [{
                                    text: '详情',
                                    url: Table.init.detail_url,
                                    auth:'detail',
                                    icon: 'iconfont icon-zoom-in-line',
                                    class: 'layui-btn layui-btn-xs layui-btn-normal btn-dialog',
                                }],
                            ]
                        }
                    ]
                ],
                page: {}
            });

            Table.api.bindevent();
        },
    };
    return Controller;
});