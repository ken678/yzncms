/**
 @ Name：简单封下基本操作
 */
layui.define(['layer', 'table'], function(exports) {
    var layer = layui.layer;

    var MOD_NAME = 'yzn';
    yzn = {
        config: {
            shade: [0.02, '#000'],
        },
        parame: function(param, defaultParam) {
            return param !== undefined ? param : defaultParam;
        },
        request: {
            post: function(option, ok, no, ex) {
                return yzn.request.ajax('post', option, ok, no, ex);
            },
            get: function(option, ok, no, ex) {
                return yzn.request.ajax('get', option, ok, no, ex);
            },
            ajax: function(type, option, ok, no, ex) {
                type = type || 'get';
                option.url = option.url || '';
                option.data = option.data || {};
                option.prefix = option.prefix || false;
                option.statusName = option.statusName || 'code';
                option.statusCode = option.statusCode || 1;
                ok = ok || function(res) {};
                no = no || function(res) {
                    var msg = res.msg == undefined ? '返回数据格式有误' : res.msg;
                    yzn.msg.error(msg);
                    return false;
                };
                ex = ex || function(res) {};
                if (option.url == '') {
                    yzn.msg.error('请求地址不能为空');
                    return false;
                }
                if (option.prefix == true) {
                    //option.url = yzn.url(option.url);
                }
                var index = yzn.msg.loading('加载中');
                $.ajax({
                    url: option.url,
                    type: type,
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    dataType: "json",
                    data: option.data,
                    timeout: 60000,
                    success: function(res) {
                        yzn.msg.close(index);
                        if (eval('res.' + option.statusName) == option.statusCode) {
                            return ok(res);
                        } else {
                            return no(res);
                        }
                    },
                    error: function(xhr, textstatus, thrown) {
                        yzn.msg.error('Status:' + xhr.status + '，' + xhr.statusText + '，请稍后再试！', function() {
                            ex(this);
                        });
                        return false;
                    }
                });
            }
        },
        msg: {
            // 成功消息
            success: function(msg, callback) {
                if (callback === undefined) {
                    callback = function() {}
                }
                var index = layer.msg(msg, { icon: 1, shade: yzn.config.shade, scrollbar: false, time: 2000, shadeClose: true }, callback);
                return index;
            },
            // 失败消息
            error: function(msg, callback) {
                if (callback === undefined) {
                    callback = function() {}
                }
                var index = layer.msg(msg, { icon: 2, shade: yzn.config.shade, scrollbar: false, time: 3000, shadeClose: true }, callback);
                return index;
            },
            // 警告消息框
            alert: function(msg, callback) {
                var index = layer.alert(msg, { end: callback, scrollbar: false });
                return index;
            },
            // 对话框
            confirm: function(msg, ok, no) {
                var index = layer.confirm(msg, { title: '操作确认', btn: ['确认', '取消'] }, function() {
                    typeof ok === 'function' && ok.call(this);
                }, function() {
                    typeof no === 'function' && no.call(this);
                    self.close(index);
                });
                return index;
            },
            // 消息提示
            tips: function(msg, time, callback) {
                var index = layer.msg(msg, { time: (time || 3) * 1000, shade: this.shade, end: callback, shadeClose: true });
                return index;
            },
            // 加载中提示
            loading: function(msg, callback) {
                var index = msg ? layer.msg(msg, { icon: 16, scrollbar: false, shade: this.shade, time: 0, end: callback }) : layer.load(2, { time: 0, scrollbar: false, shade: this.shade, end: callback });
                return index;
            },
            // 关闭消息框
            close: function(index) {
                return layer.close(index);
            }
        },
    }

    exports(MOD_NAME, yzn);
});