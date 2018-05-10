layui.use(['element', 'layer', 'form'], function() {
    var element = layui.element,
        layer = layui.layer,
        $ = layui.jquery,
        form = layui.form;


    $('[title]').hover(function() {
        var title = $(this).attr('title');
        layer.tips(title, $(this))
    }, function() {
        layer.closeAll('tips')
    })

    //通用添加
    $(".com_add_btn").click(function(){
       addNews('',$(this).attr('url'));
    })

    //添加文章
    function addNews(edit,url){
        var index = layui.layer.open({
            title : "新增数据",
            type : 2,
            content : url,
            success : function(layero, index){
                var body = layui.layer.getChildFrame('body', index);
                if(edit){
                    body.find(".newsName").val(edit.newsName);
                    body.find(".abstract").val(edit.abstract);
                    body.find(".thumbImg").attr("src",edit.newsImg);
                    body.find("#news_content").val(edit.content);
                    body.find(".newsStatus select").val(edit.newsStatus);
                    body.find(".openness input[name='openness'][title='"+edit.newsLook+"']").prop("checked","checked");
                    body.find(".newsTop input[name='newsTop']").prop("checked",edit.newsTop);
                    form.render();
                }
                setTimeout(function(){
                    layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            }
        })
        layui.layer.full(index);
        //改变窗口大小时，重置弹窗的宽高，防止超出可视区域（如F12调出debug的操作）
        $(window).on("resize",function(){
            layui.layer.full(index);
        })
    }

    //通用表单post提交
    $('.ajax-post').on('click', function(e) {
        var target, query, form1;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm = false;

        form1 = $('.' + target_form);
        if ($(this).attr('hide-data') === 'true') {
            form1 = $('.hide-data');
            query = form1.serialize();
        } else if (form1.get(0) == undefined) {
            return false;
        } else if (form1.get(0).nodeName == 'FORM') {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            if ($(this).attr('url') !== undefined) {
                target = $(this).attr('url');
            } else {
                target = form1.get(0).action;
            }
            query = form1.serialize();
        } else if (form1.get(0).nodeName == 'INPUT' || form1.get(0).nodeName == 'SELECT' || form1.get(0).nodeName == 'TEXTAREA') {
            form1.each(function(k, v) {
                if (v.type == 'checkbox' && v.checked == true) {
                    nead_confirm = true;
                }
            })
            if (nead_confirm && $(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = form1.serialize();
        } else {
            if ($(this).hasClass('confirm')) {
                if (!confirm('确认要执行该操作吗?')) {
                    return false;
                }
            }
            query = form1.find('input,select,textarea').serialize();
        }

        $.post(target, query).success(function(data) {
            if (data.status == 1) {

            } else {

            }
        });
        return false;
    });
});