define(['jquery', 'table', 'form','layui','clipboard'], function($, Table, Form,layui, ClipboardJS) {
    var form = layui.form;

    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                add_url: "general.config/add" + (Config.group ? "?groupType=" + Config.group : ""),
                edit_url: "general.config/edit",
                delete_url: 'general.config/del',
                multi_url: 'general.config/multi',
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'add'],
                url: 'general.config/index'+ (Config.group ? "?group=" + Config.group : ""),
                search: false,
                cols: [
                    [
                        { field: 'listorder', width: 70, title: '排序', edit: 'text' },
                        { field: 'name', align: "left", title: '名称' },
                        { field: 'title', align: "left", title: '标题' },
                        { field: 'ftitle', width: 150, title: '类型' },
                        { field: 'update_time', width: 200, title: '更新时间' },
                        { field: 'status', title: '状态', width: 100, unresize: true, templet: Table.formatter.switch },
                        { fixed: 'right', width: 160, title: '操作', toolbar: '#barTool' }
                    ]
                ],
            });

            //点击复制
            var clipboard = new ClipboardJS('.copy');
            clipboard.on('success', function(e) {
                Layer.msg("复制成功",{ icon: 1});
            });
            clipboard.on('error', function(e) {
                Layer.msg("复制失败！请手动调用",{ icon: 2});
            });

            Table.api.bindevent();

        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        setting: function() {
            Form.api.bindevent($("form.layui-form"));
        },
        api: {
            bindevent: function() {
                form.on('select(fieldType)', function(data) {
                    if (data.value == 'checkbox' || data.value == 'select' || data.value == 'selects' || data.value == 'radio' || data.value == 'selectpage' || data.value == 'custom') {
                        if (data.value == 'selectpage') {
                            $('#options').html($('#options2').html());
                        }else if(data.value == 'custom'){
                            $('#options').html($('#options3').html());   
                        }else{
                            $('#options').html($('#options1').html());   
                        }
                        $('#options').show();
                        form.render();
                    }else{
                        $('#options').hide();
                    }
                });

                Form.api.bindevent($("form.layui-form"));
            }
        }
    };
    return Controller;
});