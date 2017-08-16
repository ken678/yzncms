$(function() {
    //鼠标移动tips提示
    $(".itip").each(function(i,n){
        $(n).hover(function(){itip($(n).attr("alt"),$(n),undefined,$(n).hasClass("ifixed"));},function(){layer.closeAll('tips');});
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
function itip(msg, selector, ipoint, ifixed){ipoint=ipoint==undefined?1:ipoint;layer.tips(msg,selector,{tips:[ipoint,'#333'],fixed:ifixed,time:6000});}