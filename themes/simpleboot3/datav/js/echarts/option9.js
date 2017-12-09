var option9 = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'none'
        },
        formatter: function(params) {
            return params[0].name + ': ' + params[0].value;
        }
    },
    xAxis: {
        data: ['驯鹿', '火箭', '飞机', '高铁', '轮船', '汽车', '跑步', '步行', ],
        axisTick: {
            show: false
        },
        axisLine: {
            show: false
        },
        axisLabel: {
            textStyle: {
                color: '#e54027'
            }
        }
    },
    yAxis: {
        splitNumber: 3,
        max: '100',
        min: '30',
        splitLine: {
            show: false
        },
        axisTick: {
            show: false
        },
        axisLine: {
            show: false
        },
        axisLabel: {
            show: false
        }
    },

    grid: {
        left: '3%',
        right: '4%',
        top: '20%',
        bottom: '3%',
        containLabel: true
    },

    color: ['#e54027'],
    series: [{
        name: 'hill',
        type: 'pictorialBar',
        barCategoryGap: '-130%',
        //symbol: 'path://M0,5 L10,0 L5,10 L0,10 z',
        symbol: 'path://M0,10 L10,10 C5.5,10 5.5,5 5,0 C4.5,5 4.5,10 0,10 z',
        itemStyle: {
            normal: {
                opacity: 0.5
            },
            emphasis: {
                opacity: 1
            }
        },
        data: [70, 60, 65, 48, 42, 90, 52, 61],
        z: 10
    },
    {
        name: 'glyph',
        type: 'pictorialBar',
        barGap: '-100%',
        symbolPosition: 'end',
        symbolSize: 10,
        symbolOffset: [0, '-120%'],
        data: [70, 60, 65, 48, 42, 90, 52, 61],
    }]
};