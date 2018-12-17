<script src="{{URL::asset('js/echarts.common.min.js')}}"></script>
<div id="main" style="width:100%;height:{{$height}}px"></div>
{{--<div id="main" style="width: {{$width}}px;height:{{$height}}px;"></div>--}}
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));

    // 指定图表的配置项和数据

    var colors = ['#5793f3', '#d14a61', '#675bba'];


    option = {
        color: colors,

        tooltip: {
            trigger: 'none',
            axisPointer: {
                type: 'cross'
            }
        },
        legend: {
            data:['{{$data_label}}']
        },
        grid: {
            top: 70,
            bottom: 50
        },
        xAxis: [
            {
                type: 'category',
                axisTick: {
                    alignWithLabel: true
                },
                axisLine: {
                    onZero: true,
//                    lineStyle: {
//                        color: colors[0]
//                    }
                },
                axisPointer: {
                    label: {
                        formatter: function (params) {
                            return '{!! $data_label !!}  ' + params.value
                                + (params.seriesData.length ? '：' + params.seriesData[0].data : '');
                        }
                    }
                },
                data: JSON.parse('{!! $labels !!}')
            },
        ],
        yAxis: [
            {
                type: 'value'
            }
        ],
        series: [
            {
                name:'{{$data_label}}',
                type:'line',
                smooth: true,
                data: JSON.parse('{!! $data !!}')
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>