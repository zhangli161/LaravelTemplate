<canvas id="myChart" width="{{$width}}" height="width"></canvas>
<script>
    $(function () {
        var ctx = document.getElementById("myChart").getContext('2d');
        var data=JSON.parse('{!! $data !!}');
        var options=JSON.parse('{!! $options !!}');
        console.log("data:",data,"options:",options)
        var myChart = new Chart(ctx, {
            type: '{{$type}}',
            data:data,
            options: options
        });
    });
</script>