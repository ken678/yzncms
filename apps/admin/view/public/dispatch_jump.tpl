{__NOLAYOUT__}<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>跳转提示</title>
    <style type="text/css">
    body, ul, ol, li, dl, dt, dd, p, h1, h2, h3, h4, h5, h6,form{margin: 0; padding: 0; }
     body, td, input, textarea, select, button {color: #555; font-size: 12px; font-family: "Microsoft Yahei", "Lucida Grande", Verdana, Lucida, Helvetica, Arial, sans-serif; }
    .msgpage {background-color: #FFF; width: 100%; height: 100%; }
    .msgbox {background-color: #FFF; width: 480px; height: 200px; margin: 0 0 0 -240px; border: solid 1px #E7E7E7; border-radius: 5px; position: absolute; z-index: 1; top: 20%; left: 50%; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
    .msgbox .pic {background: url(__STATIC__/admin/images/msg.png) no-repeat 0 0; width: 90px; height: 90px; position: absolute; z-index: 1; top: 30px; left: 40px; }
    .msgbox .button {position: absolute; z-index: 1; top: 115px; left: 140px; }
    .msgbox .con {font-size: 24px; font-weight: lighter; color: #333; position: absolute; z-index: 1; top: 30px; left: 140px; }
    .msgbox .scon {line-height: 16px;font-size: 12px; color: #999; position: absolute; z-index: 1; top: 70px; left: 140px; }
    a#ncap-btn {font: normal 12px/20px "microsoft yahei"; text-decoration: none; color: #777; background-color: #F5F5F5; text-align: center; vertical-align: middle; display: inline-block; height: 20px; padding: 2px 9px; border: solid 1px; border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC; border-radius: 3px; cursor: pointer; }
    a:hover.ncap-btn { text-decoration: none; color: #333; background-color: #E6E6E6; border-color: #CFCFCF #CFCFCF #B3B3B3 #CFCFCF; }
    </style>
</head>
<body>
    <div class="msgpage">
        <div class="msgbox">
            <div class="pic"></div>
            <div class="con"><?php echo(strip_tags($msg));?></div>
            <div class="scon">
                <p class="jump">
                    页面如不能自动跳转，选择手动操作...<br> 等待时间： <b id="wait"><?php echo($wait);?></b>&nbsp;秒
                </p>
            </div>
            <div class="button">
                <a href="<?php echo($url);?>" id="ncap-btn">立即跳转</a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('ncap-btn').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
