define(['jquery','echarts', 'echarts-theme'], function($,Echarts, undefined) {

    var Controller = {
        index: function() {
            // 基于准备好的dom，初始化echarts实例
            var myChart = Echarts.init(document.getElementById('echart'), 'walden');

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '',
                    subtext: ''
                },
                color: [
                    "#36a3f7",
                    "#34bfa3",
                    "#f6c532",
                    "#f4516c",
                    "#8c7ae6",
                    "#00bcd4"
                ],
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['注册会员数']
                },
                toolbox: {
                    show: false,
                    feature: {
                        magicType: {show: true, type: ['stack', 'tiled']},
                        saveAsImage: {show: true}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: Config.column,
                    splitLine: {
                        show: true,
                        lineStyle: {
                            color: '#e0e0e0',
                            width: 1,
                            type: 'dashed'
                        }
                    },
                },
                yAxis: {
                    splitLine: {
                        show: true,
                        lineStyle: {
                            color: '#e0e0e0', 
                            width: 1, 
                            type: 'solid'
                        }
                    }
                },
                grid: [{
                    left: '5%',
                    right: '5%',
                    top: '5%',
                    bottom: '10%',
                }],
                series: [{
                    name: '注册会员数',
                    type: 'line',
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8,
                    itemStyle: {
                        color: '#36a3f7',
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    lineStyle: {
                        width: 3,
                        shadowColor: 'rgba(0,0,0,0.1)',
                        shadowBlur: 10,
                        shadowOffsetY: 5
                    },
                    areaStyle: {
                        // 使用渐变色填充区域
                        color: new Echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: 'rgba(54, 163, 247, 0.3)' },
                            { offset: 1, color: 'rgba(54, 163, 247, 0.1)' }
                        ])
                    },
                    // 添加悬停效果
                    emphasis: {
                        itemStyle: {
                            color: '#fff',
                            borderColor: '#36a3f7',
                            borderWidth: 2 
                        }
                    },
                    data: Config.userdata
                }],
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);

            $(window).resize(function () {
                myChart.resize();
            });

            $(document).on("click", ".btn-refresh", function () {
                setTimeout(function () {
                    myChart.resize();
                }, 0);
            });

            //获取系统时间
            var newDate = '';
            getLangDate();
            //值小于10时，在前面补0
            function dateFilter(date) {
                if (date < 10) { return "0" + date; }
                return date;
            }

            function getLangDate() {
                var dateObj = new Date(); //表示当前系统时间的Date对象
                var year = dateObj.getFullYear(); //当前系统时间的完整年份值
                var month = dateObj.getMonth() + 1; //当前系统时间的月份值
                var date = dateObj.getDate(); //当前系统时间的月份中的日
                var day = dateObj.getDay(); //当前系统时间中的星期值
                var weeks = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];
                var week = weeks[day]; //根据星期值，从数组中获取对应的星期字符串
                var hour = dateObj.getHours(); //当前系统时间的小时值
                var minute = dateObj.getMinutes(); //当前系统时间的分钟值
                var second = dateObj.getSeconds(); //当前系统时间的秒钟值
                var timeValue = "" + ((hour >= 12) ? (hour >= 18) ? "晚上" : "下午" : "上午"); //当前时间属于上午、晚上还是下午
                newDate = dateFilter(year) + "年" + dateFilter(month) + "月" + dateFilter(date) + "日 " + " " + dateFilter(hour) + ":" + dateFilter(minute) + ":" + dateFilter(second);
                document.getElementById("nowTime").innerHTML = "亲爱的"+ Config.username +"，" + timeValue + "好！ 欢迎使用YznCMS，当前时间为： " + newDate + "　" + week;
                setTimeout(getLangDate, 1000);
            }

            //icon动画
            $(".panel a").hover(function() {
                $(this).find(".layui-anim").addClass("layui-anim-scaleSpring");
            }, function() {
                $(this).find(".layui-anim").removeClass("layui-anim-scaleSpring");
            })
        }
    };

    return Controller;
});