var myChart2 = echarts.init(document.getElementById('main2'));

var option2 = {

    title: {
        text: '多雷达图'
    },
    series: {
        type: 'pie',
        radius: '50%',
        data: [
            {value:235, name:'视频广告'},
            {value:274, name:'联盟广告'},
            {value:310, name:'邮件营销'},
            {value:335, name:'直接访问'},
            {value:400, name:'搜索引擎'}
        ],
        roseType: 'angle',
        itemStyle: {
            normal: {
                shadowBlur: 200,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
    }
};
myChart2.setOption(option2);