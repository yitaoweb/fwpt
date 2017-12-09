var option7 = {

    tooltip: {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        show: false,
        orient: 'vertical',
        x: 'left',
        data: ['准会员', '普通会员', '高级会员', 'VIP会员', '超级VIP会员']
    },
    toolbox: {
        show: false,
        feature: {
            mark: {
                show: false
            },
            dataView: {
                show: true,
                readOnly: false
            },

            restore: {
                show: true
            },
            saveAsImage: {
                show: true
            }
        }
    },
    calculable: false,
    series: [

    {
        name: '级别占比',
        selectedMode: 'single',
        type: 'pie',
        radius: [50, 35],

        // for funnel
        x: '50%',
        width: '35%',
        funnelAlign: 'left',

        data: [{
            value: 335,
            name: '准会员'
        },
        {
            value: 310,
            name: '普通会员'
        },
        {
            value: 234,
            name: '高级会员'
        },
        {
            value: 234,
            name: 'VIP会员'
        },
        {
            value: 234,
            name: '超级VIP会员'
        }]
    }]
};