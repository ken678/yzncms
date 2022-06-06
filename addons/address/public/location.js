layui.define(['layer', 'locationX'], function (exports) {
    var $ = layui.jquery,
        layer = layui.layer,
        MOD_NAME = "location",
        GPS = layui.locationX;

    
    var tpl0 = '<div class="cur-load0"></div>\n' +
            '<div class="cur-load1"></div>\n' +
            '<div class="ew-map-select-tool" style="position: relative;">\n' +
            '    经度：<input id="lng" class="layui-input  inline-block" style="width: 190px;" autocomplete="off"/>\n' +
            '    &nbsp;&nbsp;&nbsp;纬度：<input id="lat" class="layui-input  inline-block" style="width: 190px;" autocomplete="off"/>\n' +
            '    <button id="ew-map-select-btn-ok" class="layui-btn icon-btn pull-right" type="button"><i\n' +
            '            class="layui-icon">&#xe605;</i>确定\n' +
            '    </button>\n' +
            '</div>\n' +
            '<div id="map" style="width: 100%;height: calc(100% - 48px);"></div>';
    
    var tpl1 ='<div class="cur-load0"></div>\n' +
            '<div class="cur-load1"></div>\n' +
            '<div class="ew-map-select-tool" style="position: relative;">\n' +
            '    搜索：<input id="ew-map-select-input-search" class="layui-input icon-search inline-block" style="width: 190px;" placeholder="输入关键字搜索" autocomplete="off" />\n' +
            '    <div id="ew-map-select-tips" class="ew-map-select-search-list layui-hide" style="left: 0px;width: 248px;"></div>\n' +
            '    &nbsp;&nbsp;经度：<input id="lng" class="layui-input  inline-block" style="width: 190px;" autocomplete="off" />\n' +
            '    &nbsp;&nbsp;&nbsp;纬度：<input id="lat" class="layui-input  inline-block" style="width: 190px;" autocomplete="off" />\n' +
            '    <button id="ew-map-select-btn-ok" class="layui-btn icon-btn pull-right" type="button"><i class="layui-icon">&#xe605;</i>确定</button>\n' +
            '</div>\n' +
            '<div class="map-select">\n' +
            '\n' +
            '    <div id="map" style="width: 600px;height: 505px;float: right;"></div>\n' +
            '    <div id="ew-map-select-poi" class="layui-col-sm5 ew-map-select-search-list ew-map-select-poi">\n' +
            '    </div>\n' +
            '\n' +
            '</div>';
  

    var obj = function (config) {

        this.config = {
            // 默认中心点位置是北京天安门，所有坐标系都用此坐标，偏的不大
            type: 0, // 0 : 仅定位  1： 带有搜索的定位
            longitude: 116.404,
            latitude: 39.915,
            title: '定位',
            zoom: 18,
            apiType: "baiduMap",
            coordinate: "baiduMap",
            mapType: 0,
            searchKey: '村',
            init: function () {
                return {longitude: 116.404, latitude: 39.915};
            },
            success: function () {

            },
            onClickTip: function (data) {
                console.log(data);
            }

        }

        this.config = $.extend(this.config, config);

        // 初始化经纬度信息
        var initData = this.config.init();
        this.config.longitude = initData.longitude;
        this.config.latitude = initData.latitude;

        this.lng = this.config.longitude;
        this.lat = this.config.latitude;
        // 转换初始坐标
        this.initCoordinate = function (lng, lat) {
            var o = this;
            if (o.config.apiType == o.config.coordinate) {
                return {lng: lng, lat: lat};
            } else if (o.config.apiType == 'baiduMap' && o.config.coordinate == 'tiandiMap') {
                var res = GPS.WGS84_bd(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'tiandiMap' && o.config.coordinate == 'baiduMap') {
                var res = GPS.bd_WGS84(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'gaodeMap' && o.config.coordinate == 'baiduMap') {
                var res = GPS.bd_decrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'baiduMap' && o.config.coordinate == 'gaodeMap') {
                var res = GPS.bd_encrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'gaodeMap' && o.config.coordinate == 'tiandiMap') {
                var res = GPS.gcj_encrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'tiandiMap' && o.config.coordinate == 'gaodeMap') {
                var res = GPS.gcj_decrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            }
        }

        if (this.config.longitude && this.config.latitude && this.config.mapType != this.config.coordinate) {
            var tbd = this.initCoordinate(this.config.longitude, this.config.latitude);
            this.config.longitude = tbd.lng;
            this.config.latitude = tbd.lat;
        }


        this.transformCoordinate = function (lng, lat) {
            var o = this;
            if (o.config.apiType == o.config.coordinate) {
                return {lng: lng, lat: lat};
            } else if (o.config.apiType == 'baiduMap' && o.config.coordinate == 'tiandiMap') {
                var res = GPS.bd_WGS84(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'tiandiMap' && o.config.coordinate == 'baiduMap') {
                var res = GPS.WGS84_bd(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'gaodeMap' && o.config.coordinate == 'baiduMap') {
                var res = GPS.bd_encrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'baiduMap' && o.config.coordinate == 'gaodeMap') {
                var res = GPS.bd_decrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'gaodeMap' && o.config.coordinate == 'tiandiMap') {
                var res = GPS.gcj_decrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            } else if (o.config.apiType == 'tiandiMap' && o.config.coordinate == 'gaodeMap') {
                var res = GPS.gcj_encrypt(lat, lng);
                return {lng: res.lon.toFixed(5), lat: res.lat.toFixed(5)};
            }
        }

        this.openBaiduMap = function () {
            var o = this;
            var map; // 创建地图实例
            if (o.config.mapType == 1) {
                map = new BMap.Map("map", {enableMapClick: false, mapType: BMAP_SATELLITE_MAP});
            } else if (o.config.mapType == 2) {
                map = new BMap.Map("map", {enableMapClick: false, mapType: BMAP_HYBRID_MAP});
            } else {
                map = new BMap.Map("map", {enableMapClick: false, mapType: BMAP_NORMAL_MAP});
            }
            map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
            var point = new BMap.Point(o.config.longitude ? o.config.longitude : 116.404, o.config.latitude ? o.config.latitude : 39.915); // 创建点坐标
            map.centerAndZoom(point, o.config.zoom);
            map.setDefaultCursor("url('" + layui.cache.base + "../../addons/address/img/location.cur') 17 35,auto");   //设置地图默认的鼠标指针样式
            var marker = new BMap.Marker(map.getCenter());  // 创建标注
            map.addOverlay(marker);               // 将标注添加到地图中
            map.addEventListener("click", function (e) {
                var tbd = o.transformCoordinate(e.point.lng, e.point.lat);
                //显示经纬度
                $("#lng").val(tbd.lng);
                $("#lat").val(tbd.lat);
                o.lng = tbd.lng;
                o.lat = tbd.lat;
                var point = new BMap.Point(e.point.lng, e.point.lat);
                map.removeOverlay(marker);
                marker = new BMap.Marker(point);
                map.addOverlay(marker);

                if (o.config.type==1){
                    searchNearBy(e.point.lng, e.point.lat);
                }
            });

            // 标记中心点
            var markCenter = function (lng, lat){
                var tbd = o.transformCoordinate(lng, lat);
                //显示经纬度
                $("#lng").val(tbd.lng);
                $("#lat").val(tbd.lat);
                o.lng = tbd.lng;
                o.lat = tbd.lat;
                var point = new BMap.Point(lng, lat);
                map.removeOverlay(marker);
                marker = new BMap.Marker(point);
                map.addOverlay(marker);
                if (o.config.type==1){
                    searchNearBy(lng, lat);
                }

            }

            // 搜索附近方法
            var searchNearBy = function (lng, lat){
                var point = new BMap.Point(lng, lat);

                var localSearch = new BMap.LocalSearch(point, {
                    pageCapacity: 10,
                    onSearchComplete: function (result){
                        if (localSearch.getStatus() == BMAP_STATUS_SUCCESS){
                            var htmlList = '';
                            $.each(result.Kr,function (i,ad){
                                //$.each(val.Kr,function (i,ad){
                                    htmlList += '<div data-lng="' + ad.point.lng + '" data-lat="' + ad.point.lat + '" data-title="'+ ad.title +'"  data-address="'+ ad.address +'" class="ew-map-select-search-list-item">';
                                    htmlList += '     <div class="ew-map-select-search-list-item-title">' + ad.title + '</div>';
                                    htmlList += '     <div class="ew-map-select-search-list-item-address">' + ad.address + '</div>';
                                    htmlList += '     <div class="ew-map-select-search-list-item-icon-ok layui-hide"><i class="layui-icon layui-icon-ok-circle"></i></div>';
                                    htmlList += '</div>';
                                //});
                            });
                            $('#ew-map-select-poi').html(htmlList);
                        }
                    }
                });
                localSearch.searchNearby('餐馆',point,1000);
            }

            // 初始化搜索
            if (o.config.type==1){
                o.initBaiduSearch(map,searchNearBy,markCenter);
            }

        }

        this.initBaiduSearch = function (map,searchNearBy,markCenter){
            var o = this;

            searchNearBy(o.config.longitude ? o.config.longitude : 116.404, o.config.latitude ? o.config.latitude : 39.915);

            // poi列表点击事件
            $('#ew-map-select-poi').off('click').on('click', '.ew-map-select-search-list-item', function () {
                $('#ew-map-select-tips').addClass('layui-hide');
                $('#ew-map-select-poi .ew-map-select-search-list-item-icon-ok').addClass('layui-hide');
                $(this).find('.ew-map-select-search-list-item-icon-ok').removeClass('layui-hide');
                $('#ew-map-select-center-img').removeClass('bounceInDown');
                setTimeout(function () {
                    $('#ew-map-select-center-img').addClass('bounceInDown');
                });
                var lng = $(this).data('lng');
                var lat = $(this).data('lat');

                //
                var point = new BMap.Point(lng, lat);
                map.centerAndZoom(point, map.getZoom());

                markCenter(lng, lat);
                var title =  $(this).data('title');
                var address =  $(this).data('address');
                o.config.onClickTip({title:title,address:address,lng:lng,lat:lat});
            });

            // 搜索提示
            var $inputSearch = $('#ew-map-select-input-search');
            $inputSearch.off('input').on('input', function () {
                var keywords = $(this).val();
                var $selectTips = $('#ew-map-select-tips');
                if (!keywords) {
                    $selectTips.html('');
                    $selectTips.addClass('layui-hide');
                }

                var autoComplete = new BMap.LocalSearch('全国', {
                    pageCapacity: 10,
                    onSearchComplete: function (result){
                        if (undefined == result){
                            return ;
                        }
                        var htmlList = '';
                        $.each(result.Kr,function (i,ad){
                            htmlList += '<div data-lng="' + ad.point.lng + '" data-lat="' + ad.point.lat + '" data-title="'+ ad.title +'"  data-address="'+ ad.address +'" class="ew-map-select-search-list-item">';
                            htmlList += '     <div class="ew-map-select-search-list-item-icon-search"><i class="layui-icon layui-icon-search"></i></div>';
                            htmlList += '     <div class="ew-map-select-search-list-item-title">' + ad.title + '</div>';
                            htmlList += '     <div class="ew-map-select-search-list-item-address">' + ad.address + '</div>';
                            htmlList += '</div>';
                        });
                        $selectTips.html(htmlList);
                        if (result.Kr.length === 0) $('#ew-map-select-tips').addClass('layui-hide');
                        else $('#ew-map-select-tips').removeClass('layui-hide');
                    }
                });
                autoComplete.search(keywords);

            });
            $inputSearch.off('blur').on('blur', function () {
                var keywords = $(this).val();
                var $selectTips = $('#ew-map-select-tips');
                if (!keywords) {
                    $selectTips.html('');
                    $selectTips.addClass('layui-hide');
                }
            });
            $inputSearch.off('focus').on('focus', function () {
                var keywords = $(this).val();
                if (keywords) $('#ew-map-select-tips').removeClass('layui-hide');
            });
            // tips列表点击事件
            $('#ew-map-select-tips').off('click').on('click', '.ew-map-select-search-list-item', function () {
                $('#ew-map-select-tips').addClass('layui-hide');
                var lng = $(this).data('lng');
                var lat = $(this).data('lat');
                var point = new BMap.Point(lng, lat);
                map.centerAndZoom(point, map.getZoom());
                markCenter(lng, lat);
                var title =  $(this).data('title');
                var address =  $(this).data('address');
                o.config.onClickTip({title:title,address:address,lng:lng,lat:lat});
            });
        }

        this.openTiandiMap = function () {
            var o = this;
            var map = new T.Map("map"); // 创建地图实例
            if (o.config.mapType == 1) {
                map.setMapType(TMAP_SATELLITE_MAP);
            } else if (o.config.mapType == 2) {
                map.setMapType(TMAP_HYBRID_MAP);
            } else {
                map.setMapType(TMAP_NORMAL_MAP);
            }
            map.enableScrollWheelZoom(true); // 开启鼠标滚轮缩放
            var latLng = new T.LngLat(o.config.longitude ? o.config.longitude : 116.404, o.config.latitude ? o.config.latitude : 39.915);

            map.centerAndZoom(latLng, o.config.zoom);

            var marker = new T.Marker(latLng);  // 创建标注
            map.addOverLay(marker);// 将标注添加到地图中

            if (undefined === window.T.MarkTool) {
                setTimeout(function () {
                    initMarkerTool();
                }, 200);
            } else {
                initMarkerTool();
            }

            function initMarkerTool() {
                var markerTool = new T.MarkTool(map, {follow: true});
                markerTool.open();
                /*标注事件*/
                var mark = function (e) {
                    $.each(map.getOverlays(), function (i, marker) {
                        if (marker != e.currentMarker) {
                            map.removeOverLay(marker);
                        }
                    })
                    //显示经纬度
                    var tbd = o.transformCoordinate(e.currentLnglat.getLng(), e.currentLnglat.getLat());
                    $("#lng").val(tbd.lng);
                    $("#lat").val(tbd.lat);
                    o.lng = tbd.lng;
                    o.lat = tbd.lat;
                    markerTool = new T.MarkTool(map, {follow: true});
                    markerTool.open();
                    markerTool.addEventListener("mouseup", mark);

                    if (o.config.type==1){
                        searchNearBy(e.currentLnglat.getLng(), e.currentLnglat.getLat());
                    }
                }
                //绑定mouseup事件 在用户每完成一次标注时触发事件。
                markerTool.addEventListener("mouseup", mark);
            }

            // 标记中心点
            var markCenter = function (lng, lat) {
                $.each(map.getOverlays(), function (i, marker) {
                    map.removeOverLay(marker);
                })
                //显示经纬度
                var tbd = o.transformCoordinate(lng, lat);
                $("#lng").val(tbd.lng);
                $("#lat").val(tbd.lat);
                o.lng = tbd.lng;
                o.lat = tbd.lat;
                var latLng = new T.LngLat(lng, lat);
                var marker = new T.Marker(latLng);  // 创建标注
                map.addOverLay(marker);// 将标注添加到地图中
                if (o.config.type==1){
                    searchNearBy(lng, lat);
                }

            }

            // 搜索附近方法
            var searchNearBy = function (lng, lat){
                var point = new T.LngLat(lng,lat);
                var localSearch = new T.LocalSearch(map, {
                    pageCapacity: 10,
                    onSearchComplete: function (result){
                        var htmlList = '';
                        $.each(result.getPois(),function (i,ad){
                            var lnglat = ad.lonlat.split(" ");
                            htmlList += '<div data-lng="' + lnglat[0] + '" data-lat="' + lnglat[1] + '" data-title="'+ ad.name +'"  data-address="'+ ad.address +'" class="ew-map-select-search-list-item">';
                            htmlList += '     <div class="ew-map-select-search-list-item-title">' + ad.name + '</div>';
                            htmlList += '     <div class="ew-map-select-search-list-item-address">' + ad.address + '</div>';
                            htmlList += '     <div class="ew-map-select-search-list-item-icon-ok layui-hide"><i class="layui-icon layui-icon-ok-circle"></i></div>';
                            htmlList += '</div>';
                        });
                        $('#ew-map-select-poi').html(htmlList);
                    }
                });
                localSearch.setQueryType(1);
                localSearch.searchNearby(o.config.searchKey,point,1000);
            }

            if (o.config.type==1){
                o.initTiandiSearch(map,searchNearBy,markCenter);
            }

        }

        this.initTiandiSearch = function (map,searchNearBy,markCenter){
            var o = this;
            searchNearBy(o.config.longitude ? o.config.longitude : 116.404, o.config.latitude ? o.config.latitude : 39.915);

            // poi列表点击事件
            $('#ew-map-select-poi').off('click').on('click', '.ew-map-select-search-list-item', function () {
                $('#ew-map-select-tips').addClass('layui-hide');
                $('#ew-map-select-poi .ew-map-select-search-list-item-icon-ok').addClass('layui-hide');
                $(this).find('.ew-map-select-search-list-item-icon-ok').removeClass('layui-hide');
                $('#ew-map-select-center-img').removeClass('bounceInDown');
                setTimeout(function () {
                    $('#ew-map-select-center-img').addClass('bounceInDown');
                });
                var lng = $(this).data('lng');
                var lat = $(this).data('lat');

                //
                var point = new T.LngLat(lng, lat);
                map.centerAndZoom(point, map.getZoom());

                markCenter(lng, lat);
                var title =  $(this).data('title');
                var address =  $(this).data('address');
                o.config.onClickTip({title:title,address:address,lng:lng,lat:lat});
            });

            // 搜索提示
            var $inputSearch = $('#ew-map-select-input-search');
            $inputSearch.off('input').on('input', function () {
                var keywords = $(this).val();
                var $selectTips = $('#ew-map-select-tips');
                if (!keywords) {
                    $selectTips.html('');
                    $selectTips.addClass('layui-hide');
                }

                var autoComplete = new T.LocalSearch(map, {
                    pageCapacity: 10,
                    onSearchComplete: function (result){
                        if (undefined == result){
                            return ;
                        }
                        var htmlList = '';
                        $.each(result.getPois(),function (i,ad){
                            var lnglat = ad.lonlat.split(" ");
                            htmlList += '<div data-lng="' + lnglat[0] + '" data-lat="' + lnglat[1] + '" data-title="'+ ad.name +'"  data-address="'+ ad.address +'" class="ew-map-select-search-list-item">';
                            htmlList += '     <div class="ew-map-select-search-list-item-title">' + ad.name + '</div>';
                            htmlList += '     <div class="ew-map-select-search-list-item-address">' + ad.address + '</div>';
                            htmlList += '     <div class="ew-map-select-search-list-item-icon-ok layui-hide"><i class="layui-icon layui-icon-ok-circle"></i></div>';
                            htmlList += '</div>';
                        });
                        $selectTips.html(htmlList);
                        if (result.getPois().length === 0) $('#ew-map-select-tips').addClass('layui-hide');
                        else $('#ew-map-select-tips').removeClass('layui-hide');
                    }
                });
                autoComplete.setQueryType(1);
                autoComplete.search(keywords);

            });
            $inputSearch.off('blur').on('blur', function () {
                var keywords = $(this).val();
                var $selectTips = $('#ew-map-select-tips');
                if (!keywords) {
                    $selectTips.html('');
                    $selectTips.addClass('layui-hide');
                }
            });
            $inputSearch.off('focus').on('focus', function () {
                var keywords = $(this).val();
                if (keywords) $('#ew-map-select-tips').removeClass('layui-hide');
            });
            // tips列表点击事件
            $('#ew-map-select-tips').off('click').on('click', '.ew-map-select-search-list-item', function () {
                $('#ew-map-select-tips').addClass('layui-hide');
                var lng = $(this).data('lng');
                var lat = $(this).data('lat');
                var point = new T.LngLat(lng, lat);
                map.centerAndZoom(point, map.getZoom());
                markCenter(lng, lat);
                var title =  $(this).data('title');
                var address =  $(this).data('address');
                o.config.onClickTip({title:title,address:address,lng:lng,lat:lat});
            });
        }

        this.openGaodeMap = function () {
            var o = this;
            // 创建地图实例
            var layers = [];
            if (o.config.mapType == '1') {
                var satellite = new AMap.TileLayer.Satellite();
                layers.push(satellite);
            } else if (o.config.mapType == '2') {
                var satellite = new AMap.TileLayer.Satellite();
                var roadNet = new AMap.TileLayer.RoadNet();
                layers.push(satellite);
                layers.push(roadNet);
            } else {
                var layer = new AMap.TileLayer();
                layers.push(layer);
            }
            var map = new AMap.Map("map",
                {
                    resizeEnable: true,
                    zoom: o.config.zoom,
                    center: [o.config.longitude ? o.config.longitude : 116.404, o.config.latitude ? o.config.latitude : 39.915],
                    layers: layers
                });
            map.setDefaultCursor("url('" + layui.cache.base + "../../addons/address/img/location_blue.cur') 17 35,auto");

            // 初始化中间点标记
            var marker = new AMap.Marker({
                icon: "https://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
                position: [o.config.longitude ? o.config.longitude : 116.404, o.config.latitude ? o.config.latitude : 39.915]
            });
            map.add(marker);
            var markCenter = function (e) {
                // 标记中心点
                map.clearMap();
                // alert('您在[ '+e.lnglat.getLng()+','+e.lnglat.getLat()+' ]的位置点击了地图！');
                //显示经纬度
                var tbd = o.transformCoordinate(e.lng, e.lat);
                $("#lng").val(tbd.lng);
                $("#lat").val(tbd.lat);
                o.lng = tbd.lng;
                o.lat = tbd.lat;
                var marker = new AMap.Marker({
                    icon: "https://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
                    position: [e.lng, e.lat]
                });
                map.add(marker);
                if (o.config.type == 1) {
                    searchNearBy(e.lng, e.lat);
                }
            }

            var clickHandler = function (e) {
                markCenter({lng: e.lnglat.getLng(), lat: e.lnglat.getLat()});
            };

            // 绑定事件
            map.on('click', clickHandler);

            // 附近搜索方法
            var searchNearBy = function (lng, lat) {
                AMap.service(['AMap.PlaceSearch'], function () {
                    var placeSearch = new AMap.PlaceSearch({
                        type: '', pageSize: 10, pageIndex: 1
                    });
                    var cpoint = [lng, lat];
                    placeSearch.searchNearBy('', cpoint, 1000, function (status, result) {
                        if (status === 'complete') {
                            var pois = result.poiList.pois;
                            var htmlList = '';
                            for (var i = 0; i < pois.length; i++) {
                                var poiItem = pois[i];
                                if (poiItem.location !== undefined) {
                                    htmlList += '<div data-lng="' + poiItem.location.lng + '" data-lat="' + poiItem.location.lat + '" data-title="'+ poiItem.name +'"  data-address="'+ poiItem.address  +'" class="ew-map-select-search-list-item">';
                                    htmlList += '     <div class="ew-map-select-search-list-item-title">' + poiItem.name + '</div>';
                                    htmlList += '     <div class="ew-map-select-search-list-item-address">' + poiItem.address + '</div>';
                                    htmlList += '     <div class="ew-map-select-search-list-item-icon-ok layui-hide"><i class="layui-icon layui-icon-ok-circle"></i></div>';
                                    htmlList += '</div>';
                                }
                            }
                            $('#ew-map-select-poi').html(htmlList);
                        }
                    });
                });
            };

            // 初始化search
            if (o.config.type == 1) {
                o.initGaodeSearch(map, markCenter, searchNearBy);
            }

        }

        this.initGaodeSearch = function (map, markCenter, searchNearBy) {
            var o = this;
            // poi列表点击事件
            $('#ew-map-select-poi').off('click').on('click', '.ew-map-select-search-list-item', function () {
                $('#ew-map-select-tips').addClass('layui-hide');
                $('#ew-map-select-poi .ew-map-select-search-list-item-icon-ok').addClass('layui-hide');
                $(this).find('.ew-map-select-search-list-item-icon-ok').removeClass('layui-hide');
                $('#ew-map-select-center-img').removeClass('bounceInDown');
                setTimeout(function () {
                    $('#ew-map-select-center-img').addClass('bounceInDown');
                });
                var lng = $(this).data('lng');
                var lat = $(this).data('lat');
                var name = $(this).find('.ew-map-select-search-list-item-title').text();
                var address = $(this).find('.ew-map-select-search-list-item-address').text();
                //
                map.setZoomAndCenter(map.getZoom(), [lng, lat]);

                markCenter({lng: lng, lat: lat});
                var title =  $(this).data('title');
                var address =  $(this).data('address');
                o.config.onClickTip({title:title,address:address,lng:lng,lat:lat});
            });

            searchNearBy(o.config.longitude ? o.config.longitude : 116.404, o.config.latitude ? o.config.latitude : 39.915);

            // 搜索提示
            var $inputSearch = $('#ew-map-select-input-search');
            $inputSearch.off('input').on('input', function () {
                var keywords = $(this).val();
                var $selectTips = $('#ew-map-select-tips');
                if (!keywords) {
                    $selectTips.html('');
                    $selectTips.addClass('layui-hide');
                }
                AMap.plugin('AMap.Autocomplete', function () {
                    var autoComplete = new AMap.Autocomplete({city: '全国'});
                    autoComplete.search(keywords, function (status, result) {
                        if (result.tips) {
                            var tips = result.tips;
                            var htmlList = '';
                            for (var i = 0; i < tips.length; i++) {
                                var tipItem = tips[i];
                                if (tipItem.location !== undefined) {
                                    htmlList += '<div data-lng="' + tipItem.location.lng + '" data-lat="' + tipItem.location.lat + '" data-title="'+ tipItem.name +'"  data-address="'+ tipItem.address  +'" class="ew-map-select-search-list-item">';
                                    htmlList += '     <div class="ew-map-select-search-list-item-icon-search"><i class="layui-icon layui-icon-search"></i></div>';
                                    htmlList += '     <div class="ew-map-select-search-list-item-title">' + tipItem.name + '</div>';
                                    htmlList += '     <div class="ew-map-select-search-list-item-address">' + tipItem.address + '</div>';
                                    htmlList += '</div>';
                                }
                            }
                            $selectTips.html(htmlList);
                            if (tips.length === 0) $('#ew-map-select-tips').addClass('layui-hide');
                            else $('#ew-map-select-tips').removeClass('layui-hide');
                        } else {
                            $selectTips.html('');
                            $selectTips.addClass('layui-hide');
                        }
                    });
                });
            });
            $inputSearch.off('blur').on('blur', function () {
                var keywords = $(this).val();
                var $selectTips = $('#ew-map-select-tips');
                if (!keywords) {
                    $selectTips.html('');
                    $selectTips.addClass('layui-hide');
                }
            });
            $inputSearch.off('focus').on('focus', function () {
                var keywords = $(this).val();
                if (keywords) $('#ew-map-select-tips').removeClass('layui-hide');
            });
            // tips列表点击事件
            $('#ew-map-select-tips').off('click').on('click', '.ew-map-select-search-list-item', function () {
                $('#ew-map-select-tips').addClass('layui-hide');
                var lng = $(this).data('lng');
                var lat = $(this).data('lat');
                map.setZoomAndCenter(map.getZoom(), [lng, lat]);
                markCenter({lng: lng, lat: lat});
                var title =  $(this).data('title');
                var address =  $(this).data('address');
                o.config.onClickTip({title:title,address:address,lng:lng,lat:lat});
            });
        }

        this.openMap = function () {
            var o = this;

            if (o.config.apiType == "baiduMap") {
                var index = layer.open({
                    type: 1,
                    area: ["850px", "600px"],
                    title: o.config.title,
                    content: o.config.type == 0 ? tpl0:tpl1,
                    success: function () {
                        // 回显数据 中心标记经纬度
                        $("#lng").val(o.lng);
                        $("#lat").val(o.lat);
                        // 渲染地图
                        if (undefined === window.BMap) {
                            $.getScript("https://api.map.baidu.com/getscript?v=2.0&ak=tCNPmUfNmy4nTR3VYW71a6IgyWMaOSUb&services=&t=20200824135534", function () {
                                o.openBaiduMap();
                            });
                        } else {
                            o.openBaiduMap();
                        }
                        // 绑定事件
                        $("#ew-map-select-btn-ok").on("click", function () {
                            o.config.success({lng: o.lng ? o.lng : 116.404, lat: o.lat ? o.lat : 39.915});
                            layer.close(index);
                        })
                    }
                });
            } else if (o.config.apiType == "tiandiMap") {
                var index = layer.open({
                    type: 1,
                    area: ["850px", "600px"],
                    title: o.config.title,
                    content: o.config.type == 0 ? tpl0:tpl1,
                    success: function () {
                        // 回显数据 中心标记经纬度
                        $("#lng").val(o.lng);
                        $("#lat").val(o.lat);
                        // 渲染地图
                        if (undefined === window.T) {
                            $.getScript("http://api.tianditu.gov.cn/api?v=4.0&tk=a8718394c98e9ae85b0d7af352653ce2&callback=init", function () {
                                o.openTiandiMap();
                            })
                        } else {
                            o.openTiandiMap();
                        }
                        // 绑定事件
                        $("#ew-map-select-btn-ok").on("click", function () {
                            o.config.success({lng: o.lng ? o.lng : 116.404, lat: o.lat ? o.lat : 39.915});
                            layer.close(index);
                        })
                    }
                });

            } else if (o.config.apiType == "gaodeMap") {
                var index = layer.open({
                    type: 1,
                    area: ["850px", "600px"],
                    title: o.config.title,
                    content: o.config.type == 0 ? tpl0:tpl1,
                    success: function () {
                        // 回显数据 中心标记经纬度
                        $("#lng").val(o.lng);
                        $("#lat").val(o.lat);
                        // 渲染地图
                        if (undefined === window.AMap) {
                            $.getScript("https://webapi.amap.com/maps?v=1.4.14&key=006d995d433058322319fa797f2876f5", function () {
                                o.openGaodeMap();
                            });
                        } else {
                            o.openGaodeMap();
                        }
                        // 绑定事件
                        $("#ew-map-select-btn-ok").on("click", function () {
                            o.config.success({lng: o.lng ? o.lng : 116.404, lat: o.lat ? o.lat : 39.915});
                            layer.close(index);
                        })
                    }
                });
            }

        }

    };


    layui.link(layui.cache.base + '../../addons/address/location.css');  // 加载css

    /*导出模块,用一个location对象来管理obj，不需要外部new obj*/
    var location = function () {
    }
    location.prototype.render = function (elem, config) {
        $(elem).on("click", function () {
            var _this = new obj(config);
            _this.openMap();
        })
    }
    var locationObj = new location();
    exports(MOD_NAME, locationObj);
})