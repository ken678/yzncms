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