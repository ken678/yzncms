$(function() {
    //鼠标移动tips提示
    $(".itip").each(function(i,n){
        $(n).hover(function(){itip($(n).attr("alt"),$(n),undefined,$(n).hasClass("ifixed"));},function(){layer.closeAll('tips');});
    });

    //全选的实现
    $(".check-all").click(function(){
        $(".ids").prop("checked", this.checked);
    });
    $(".ids").click(function(){
        var option = $(".ids");
        option.each(function(i){
            if(!this.checked){
                $(".check-all").prop("checked", false);
                return false;
            }else{
                $(".check-all").prop("checked", true);
            }
        });
    });

    //ajax get请求
    $('.ajax-get').click(function(){
        var target;
        var that = this;
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.code==1) {
                    if (data.url) {
                        layer.msg(data.msg + ' 页面即将自动跳转~', {icon: 1});
                    }else{
                        layer.msg(data.msg, {icon: 1});
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    layer.msg(data.msg, {icon: 1});
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                        }
                    },1500);
                }
            });

        }
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function(){
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm=false;
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);

            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            }else if (form.get(0)==undefined){
                return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                    target = $(this).attr('url');
                }else{
                    target = form.get(0).action;
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
                if (data.code==1) {
                    if (data.url) {
                        layer.msg(data.msg + ' 页面即将自动跳转~', {icon: 1});
                    }else{
                        layer.msg(data.msg , {icon: 1});
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled',false);
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    layer.msg(data.msg , {icon: 1});
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled',false);
                        }
                    },1500);
                }
            });
        }
        return false;
    });


    //操作提示缩放动画
    $("#checkZoom").toggle(
        function() {
            $("#explanation").animate({
                color: "#FFF",
                backgroundColor: "#4FD6BE",
                width: "90",
                height: "20",
            },300);
            $("#explanationZoom").hide();
        },
        function() {
            $("#explanation").animate({
                color: "#2CBCA3",
                backgroundColor: "#EDFBF8",
                width: "99%",
                height: "20",
            },300,function() {
                $(this).css('height', '100%');
            });
            $("#explanationZoom").show();
        }
    );

    //自定义radio样式
    $(".cb-enable").click(function(){
        var parent = $(this).parents('.onoff');
        $('.cb-disable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.checkbox',parent).attr('checked', true);
    });
    $(".cb-disable").click(function(){
        var parent = $(this).parents('.onoff');
        $('.cb-enable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.checkbox',parent).attr('checked', false);
    });


});
//tip提示
function itip(msg, selector, ipoint, ifixed){ipoint=ipoint==undefined?1:ipoint;layer.tips(msg,selector,{tips:[ipoint,'#333'],fixed:ifixed,time:6000});}
//新窗口打开
function openwinx(url,name,w,h) {
    if(!w) w=screen.width;
    if(!h) h=screen.height;
    window.open(url,name);
}