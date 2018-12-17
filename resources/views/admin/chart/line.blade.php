<canvas id="myChart" width="{{$width}}" height="width"></canvas>
<script>
    $(function () {
        var ctx = document.getElementById("myChart").getContext('2d');


        var chart_labels=JSON.parse('{!! $labels !!}');
//        var chart_labels=["a","b","c","d","e","f","g","h","i","j","k","l"]
        var chart_data_label=JSON.parse('{!! $data_label !!}');
        var chart_data=JSON.parse('{!! $data !!}');
//        var chart_data=[
//            1,5,3,6,7,11
//            ];
        var data = {
            labels: chart_labels,
            datasets: [
                {
                    label: chart_data_label,
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: chart_data,
                }
            ]
        };
        var options={

            ///Boolean - 是否在图表中显示网格线
            scaleShowGridLines : true,

            //String - 网格线颜色
            scaleGridLineColor : "rgba(0,0,0,.05)",

            //Number - 网格线宽度
            scaleGridLineWidth : 1,

            //Boolean - 是否显示水平线（X轴除外）
            scaleShowHorizontalLines: true,

            //Boolean - 是否显示垂直线（Y轴除外）
            scaleShowVerticalLines: true,

            //Boolean - 直线是否在点之间弯曲
            bezierCurve : true,

            //Number - Tension of the bezier curve between points
//            点间贝塞尔曲线的张力
            bezierCurveTension : 0.4,

            //Boolean - 是否为每一点显示一个点
            pointDot : true,

            //Number - 每个点以像素为单位的半径
            pointDotRadius : 4,

            //Number - 点划线像素宽度
            pointDotStrokeWidth : 1,

            //Number - 为满足绘制点之外的命中检测而添加到半径的额外量
            pointHitDetectionRadius : 20,

            //Boolean - 是否显示数据集的路径
            datasetStroke : true,

            //Number - Pixel width of dataset stroke
            datasetStrokeWidth : 2,

            //Boolean - Whether to fill the dataset with a colour
            datasetFill : true,

            //String - A legend template
            {{--legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"--}}

            };;
        console.log("data:",data,"options:",options)
        var myChart = new Chart(ctx, {
            type: 'line',
            data:data,
            options: options
        });
    });
</script>