//添加/修改文章时，标题加粗
function input_font_bold() {
    if ($('#title').css('font-weight') == '700' || $('#title').css('font-weight') == 'bold') {
        $('#title').css('font-weight', 'normal');
        $('#style_font_weight').val('');
    } else {
        $('#title').css('font-weight', 'bold');
        $('#style_font_weight').val('bold');
    }
}

//输入长度提示
function strlen_verify(obj, checklen, maxlen) {
    var v = obj.value,
        charlen = 0,
        maxlen = !maxlen ? 200 : maxlen,
        curlen = maxlen,
        len = strlen(v);
    var charset = 'utf-8';
    for (var i = 0; i < v.length; i++) {
        if (v.charCodeAt(i) < 0 || v.charCodeAt(i) > 255) {
            curlen -= charset == 'utf-8' ? 2 : 1;
        }
    }
    if (curlen >= len) {
        $('#' + checklen).html(curlen - len);
    } else {
        obj.value = mb_cutstr(v, maxlen, true);
    }
}

//长度统计
function strlen(str) {
    return ($.browser.msie && str.indexOf('\n') != -1) ? str.replace(/\r?\n/g, '_').length : str.length;
}

function mb_cutstr(str, maxlen, dot) {
    var len = 0;
    var ret = '';
    var dot = !dot ? '...' : '';

    maxlen = maxlen - dot.length;
    for (var i = 0; i < str.length; i++) {
        len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (charset == 'utf-8' ? 3 : 2) : 1;
        if (len > maxlen) {
            ret += dot;
            break;
        }
        ret += str.substr(i, 1);
    }
    return ret;
}

/**
swf上传完回调方法
uploadid dialog id
name dialog名称
textareaid 最后数据返回插入的容器id
funcName 回调函数
args 参数
module 所属模块
catid 栏目id
authkey 参数密钥，验证args
**/
function flashupload(uploadid, name, textareaid, funcName, args, module, catid, authkey) {
    var args = args ? '?args=' + args : '';
    var setting = '&module=' + module + '&catid=' + catid + '&authkey=' + authkey;
    var status = false;
    //检查是否有上传权限
    /*$.ajax({
        type: "GET",
        url: 'index.php?a=competence&m=Attachments&g=Attachment' + args + setting,
        dataType: "json",
        async: false,
        success: function (json) {
            if (json.status == false) {
                //isalert(json.info || '没有上传权限！');
                alert('没有上传权限');
                status = false;
                return false;
            }
            status = true;
        }
    });*/
    layer.open({
        btn: ['确定', '取消'],
        yes: function(){
           funcName.apply(this, [this, textareaid]);
           layer.closeAll();
        }
        ,btn2: function(){
           layer.closeAll();
        },
        id:1,
        type: 2,
        title: '上传图片',
        shadeClose: true,
        shade: false,
        maxmin: false, //开启最大化最小化按钮
        area: ['700px', '440px'],
        skin: 'layui-layer-rim', //加上边框
        content: upurl +args +setting
     });
    //funcName.apply(this, [this, textareaid]);
    /*if (status) {
        Wind.use("artDialog", "iframeTools", function () {
            art.dialog.open(GV.DIMAUB + 'index.php?a=swfupload&m=Attachments&g=Attachment' + args + setting, {
                title: name,
                id: uploadid,
                width: '650px',
                height: '420px',
                lock: true,
                fixed: true,
                background: "#CCCCCC",
                opacity: 0,
                ok: function () {
                    if (funcName) {
                        funcName.apply(this, [this, textareaid]);
                    } else {
                        submit_ckeditor(this, textareaid);
                    }
                },
                cancel: true
            });
        });
    }*/


}








//缩图上传回调
function thumb_images(uploadid, returnid) {
    var in_content = layer.getChildFrame("#att-status").html().substring(1);
    if (in_content == '' || typeof(in_content) == "undefined") return false;
    if (!IsImg(in_content)) {
        //isalert('选择的类型必须为图片类型！');
        return false;
    }
    if ($('#' + returnid + '_preview').attr('src')) {
        $('#' + returnid + '_preview').attr('src', in_content);
    }
    $('#' + returnid).val(in_content);
}

//图片上传回调
function submit_images(uploadid, returnid) {
    var d = uploadid.iframe.contentWindow;
    var in_content = d.$("#att-status").html().substring(1);
    var in_content = in_content.split('|');
    IsImg(in_content[0]) ? $('#' + returnid).attr("value", in_content[0]) : alert('选择的类型必须为图片类型');
}

//验证地址是否为图片
function IsImg(url) {
    var sTemp;
    var b = false;
    var opt = "jpg|gif|png|bmp|jpeg";
    var s = opt.toUpperCase().split("|");
    for (var i = 0; i < s.length; i++) {
        sTemp = url.substr(url.length - s[i].length - 1);
        sTemp = sTemp.toUpperCase();
        s[i] = "." + s[i];
        if (s[i] == sTemp) {
            b = true;
            break;
        }
    }
    return b;
}