var data = [220, 182, 191, 234, 190, 330, 310, 50, 200];
var markLineData = [];
for (var i = 1; i < data.length; i++) {
    markLineData.push([{
        xAxis: i - 1,
        yAxis: data[i - 1],
        value: (data[i] + data[i - 1]).toFixed(2)
    },
    {
        xAxis: i,
        yAxis: data[i]
    }]);
}

//option
var option4 = {

    tooltip: {
        trigger: 'axis'
    },
    grid: {
        left: '3%',
        right: '4%',
        top: '20%',
        bottom: '3%',
        containLabel: true
    },
    xAxis: {
        data: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    },
    yAxis: {
        splitNumber: 3,
        max: '500',
        min: '100'
    },
    series: [{
        type: 'line',
        data: data,
        markPoint: {
            data: [{
                type: 'max',
                name: '最大值'
            },
            {
                type: 'min',
                name: '最小值'
            }]
        },
        markLine: {
            smooth: true,
            effect: {
                show: true
            },
            distance: 10,
            label: {
                normal: {
                    position: 'middle'
                }
            },
            symbol: ['none', 'none'],
            data: markLineData
        }
    }]
};