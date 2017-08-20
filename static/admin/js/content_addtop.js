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