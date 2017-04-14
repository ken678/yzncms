$(function() {
	//使用title内容作为tooltip提示文字
    $(document).tooltip({
        track: true
    });

    //操作提示缩放动画
    $("#checkZoom").toggle(
        function() {
            $("#explanation").animate({
                color: "#FFF",
                backgroundColor: "#4FD6BE",
                width: "80",
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















});