var option_map = {
    title: {
        text: '“公共服务数据”',
        subtext: '',
        x: 'center',
        textStyle: {
            color: '#ccc'
        }
    },
    tooltip: {
        trigger: 'item',
        alwaysShowContent: true,
        backgroundColor: "",
        borderColor: "",
        position: [10, 10],

        extraCssText: 'width:100%;height:100%;',

        formatter: function(params) {
            var hstr = "";
            if (typeof(params.value)[2] == "undefined") {
                //return params.name + ' : ' + params.value;
                hstr += '<div class="maptip">';

                hstr += '<div class="map_span">';
                hstr += '<div class="txt">服务机构数</div>';
                hstr += '<div class="unit">单位：家</div>';
                hstr += '<div class="map_num">5000</div>';
                hstr += '</div>';

                hstr += '<div class="map_span">';
                hstr += '<div class="txt">服务机构数</div>';
                hstr += '<div class="unit">单位：家</div>';
                hstr += '<div class="map_num">5000</div>';
                hstr += '</div>';

                hstr += '<div class="map_span">';
                hstr += '<div class="txt">服务机构数</div>';
                hstr += '<div class="unit">单位：家</div>';
                hstr += '<div class="map_num">5000</div>';
                hstr += '</div>';

                hstr += '</div>';

                hstr += '<div class="maptip2">';

                hstr += '<div class="map_span">';
                hstr += '<div class="txt">服务机构数</div>';
                hstr += '<div class="unit">单位：家</div>';
                hstr += '<div class="map_num">5000</div>';
                hstr += '</div>';

                hstr += '<div class="map_span">';
                hstr += '<div class="txt">服务机构数</div>';
                hstr += '<div class="unit">单位：家</div>';
                hstr += '<div class="map_num">5000</div>';
                hstr += '</div>';

                hstr += '<div class="map_span">';
                hstr += '<div class="txt">服务机构数</div>';
                hstr += '<div class="unit">单位：家</div>';
                hstr += '<div class="map_num">5000</div>';
                hstr += '</div>';

                hstr += '</div>';

                return hstr;
                // return '<div class="box maptip">'+params.name + ' : ' + params.value+'</div>'+'<div class="box maptip2 ">'+params.name + ' : ' + params.value+'</div>';
            } else {
                return params.name + ' : ' + params.value[2];
            }
        },

        //             position: function (point, params, dom, rect, size) {
        //     $(dom).html('<div class="box maptip ">'+params.name + ' : ' + params.value+'</div>')
        // }
    },
    legend: {
        orient: 'vertical',
        y: 'bottom',
        x: 'right',
        data: ['credit_pm2.5'],
        textStyle: {
            color: '#fff'
        }
    },
    visualMap: {
        show: false,
        min: 0,
        max: 500,
        left: 'left',
        top: 'bottom',
        text: ['高', '低'],
        // 文本，默认为数值文本
        calculable: true,
        seriesIndex: [1],
        inRange: {
            // color: ['#3B5077', '#031525'] // 蓝黑
            // color: ['#ffc0cb', '#800080'] // 红紫
            // color: ['#3C3B3F', '#605C3C'] // 黑绿
            color: ['#0f0c29', '#302b63', '#24243e'] // 黑紫黑
            // color: ['#23074d', '#cc5333'] // 紫红
            // color: ['#00467F', '#A5CC82'] // 蓝绿
            // color: ['#1488CC', '#2B32B2'] // 浅蓝
            // color: ['#00467F', '#A5CC82'] // 蓝绿
            // color: ['#00467F', '#A5CC82'] // 蓝绿
            // color: ['#00467F', '#A5CC82'] // 蓝绿
            // color: ['#00467F', '#A5CC82'] // 蓝绿
        }
    },
    // toolbox: {
    //     show: true,
    //     orient: 'vertical',
    //     left: 'right',
    //     top: 'center',
    //     feature: {
    //             dataView: {readOnly: false},
    //             restore: {},
    //             saveAsImage: {}
    //             }
    // },
    geo: {
        show: true,
        map: 'changzhi',
        label: {
            normal: {
                show: false
            },
            emphasis: {
                show: false,
            }
        },
        roam: false,
        itemStyle: {
            normal: {
                areaColor: '#031525',
                borderColor: '#3B5077',
            },
            emphasis: {
                areaColor: '#2B91B7',
            }
        }
    },
    series: [{
        name: 'credit_pm2.5',
        type: 'scatter',
        coordinateSystem: 'geo',
        data: convertData(data),
        symbolSize: function(val) {
            return val[2] / 10;
        },
        label: {
            normal: {
                formatter: '{b}',
                position: 'right',
                show: true
            },
            emphasis: {
                show: true
            }
        },
        itemStyle: {
            normal: {
                color: '#05C3F9'
            }
        }
    },
    {
        type: 'map',
        map: 'jiangxi',
        geoIndex: 0,
        aspectScale: 0.75,
        //长宽比
        showLegendSymbol: false,
        // 存在legend时显示
        label: {
            normal: {
                show: false
            },
            emphasis: {
                show: false,
                textStyle: {
                    color: '#fff'
                }
            }
        },
        roam: true,
        itemStyle: {
            normal: {
                areaColor: '#031525',
                borderColor: '#3B5077',
            },
            emphasis: {
                areaColor: '#2B91B7'
            }
        },
        animation: false,
        data: data
    },
    {
        name: '点',
        type: 'scatter',
        coordinateSystem: 'geo',
        symbol: 'pin',
        symbolSize: function(val) {
            var a = (maxSize4Pin - minSize4Pin) / (max - min);
            var b = minSize4Pin - a * min;
            b = maxSize4Pin - a * max;
            return a * val[2] + b;
        },
        label: {
            normal: {
                show: true,
                textStyle: {
                    color: '#fff',
                    fontSize: 9,
                }
            }
        },
        itemStyle: {
            normal: {
                color: '#F62157',
                //标志颜色
            }
        },
        zlevel: 6,
        data: convertData(data),
    },
    {
        name: 'Top 5',
        type: 'effectScatter',
        coordinateSystem: 'geo',
        data: convertData(data.sort(function(a, b) {
            return b.value - a.value;
        }).slice(0, 5)),
        symbolSize: function(val) {
            return val[2] / 10;
        },
        showEffectOn: 'render',
        rippleEffect: {
            brushType: 'stroke'
        },
        hoverAnimation: true,
        label: {
            normal: {
                formatter: '{b}',
                position: 'right',
                show: true
            }
        },
        itemStyle: {
            normal: {
                color: '#05C3F9',
                shadowBlur: 10,
                shadowColor: '#05C3F9'
            }
        },
        zlevel: 1
    },

    ]
};