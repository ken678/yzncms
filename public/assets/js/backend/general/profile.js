define(['jquery', 'table', 'form', 'upload'], function($, Table, Form, Upload) {
    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh'],
                url: 'general.profile/index',
                cols: [
                    [
                        { field: 'id', width: 80, title: 'ID', sort: true },
                        { field: 'title', title: '标题' },
                        { field: 'url', title: '操作URL' },
                        { field: 'create_time', width: 180, title: '时间', search: 'range' },
                        { field: 'ip', width: 120, title: 'IP' },
                    ]
                ],
                page: {}
            });

            Table.api.bindevent();

            // 给上传按钮添加上传成功事件
            $("#faupload-avatar").data("upload-success", function (data) {
                var url = Backend.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                Toastr.success("上传成功！");
            });

            // 给表单绑定事件
            Form.api.bindevent($("form#profile"), function() {
                $("input[name='row[password]']").val('');
                var url = Backend.api.cdnurl($("#c-avatar").val());
                top.window.$(".layui-header .layui-layout-right .userAvatar").prop("src", url);
                return true;
            });
        }
    };
    return Controller;
});