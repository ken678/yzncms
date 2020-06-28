layui.define(['form'], function(exports) {
    var MOD_NAME = 'yznForm',
        $ = layui.$,
        form = layui.form;

    // 放大图片
    $('body').on('click', '[data-image]', function() {
        var title = $(this).attr('data-image'),
            src = $(this).attr('src'),
            alt = $(this).attr('alt');
        var photos = {
            "title": title,
            "id": Math.random(),
            "data": [{
                "alt": alt,
                "pid": Math.random(),
                "src": src,
                "thumb": src
            }]
        };
        layer.photos({
            photos: photos,
            anim: 5
        });
        return false;
    });
    exports(MOD_NAME, {});
});