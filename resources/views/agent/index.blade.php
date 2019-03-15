<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>凯莱克斯代理商管理后台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #fff;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .user {
            background: #00c0ef;
            color: #fff;
            font-size: 18px;
            padding: 16px;
            text-align: center
        }

        .commission {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #00c0ef;
            color: #fff;
            font-size: 18px;
            padding: 20px;
            line-height: 32px;
        }

        .but {
            border: 1.4px solid #fff;
            background: #00c0ef;
            font-size: 16px;
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .menu {
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            background: #fff;
        }

        .supplier_information {
            width: 50%;
            text-align: center;
            padding: 20px 10px;
            border-left: 1.4px solid #f2f2f2;
            border-bottom: 1.4px solid #f2f2f2
        }

        .title {
            margin-top: 10px;
            font-size: 16px;
            color: #666666;
        }

        .statistical_chart {
            display: flex;
            justify-content: space-around;
            font-size: 16px;
            line-height: 44px;
            background: #fff;
            color: #333333;
        }

        .select {
            color: #00c0ef;
            border-bottom: 1.4px solid #00c0ef;
        }
    </style>
</head>
<body>
<div class="user">欢迎您！{{$agent->real_name}}</div>
<div class="commission">
    <div class="row">
        <div class="col-xs-5">可提现佣金</div>
        <div class="col-xs-5" style=" font-size: 20px;">￥{{$agent->balance}}</div>

    </div>
    <div class="row">
        <div class="col-xs-5">已提现佣金</div>
        <div class="col-xs-5" style=" font-size: 20px;">￥{{$agent->cashed}}</div>
    </div>
    <div >
        <input class="but" type="button" value="提现" id="cash_withdrawal">
    </div>
</div>


<div>
    <div class="menu">
        <div class="supplier_information" id="information">
            <img src="/images/image_04.png" alt="" width="30" height="30">
            <p class="title">代理商信息</p>
        </div>

        <div class="supplier_information" id="financial_flow">
            <img src="/images/image_03.png" alt="" width="30" height="30">
            <p class="title">财务流水</p>
        </div>
    </div>

    <div class="menu">
        <div class="supplier_information" id="record">
            <img src="/images/image_01.png" alt="" width="30" height="30">
            <p class="title">提现记录</p>
        </div>

        <div class="supplier_information" id="Code">
            <img src="/images/image_02.png" alt="" width="30" height="30">
            <p class="title">推广二维码</p>
        </div>
    </div>
</div>

<!-- 统计图展示 -->

<div class="statistical_chart">
    <div class="selected select" id="fans_order">近七日粉丝订单量</div>
    <div class="selected" id="fans_increase">近七日粉丝增长量</div>
</div>

<div id="main" style="width: 100%;height:250px;background: #fff"></div>

<div class="row">
    <a class="btn btn-primary" href="{{url('agent/fans')}}">我的粉丝</a>
</div>
<script src="/js/echarts.simple.min.js"></script>
<script src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
    //提现
    $("#cash_withdrawal").click(function () {
        window.location.href = "{{url('agent/cash')}}"
    })
    // 代理商
    $("#information").click(function () {
        window.location.href = "{{url('agent/info')}}"
    })
    // 财务流水
    $("#financial_flow").click(function () {
        window.location.href = "{{url('agent/finance')}}"
    })
    // 提现记录
    $("#record").click(function () {
        window.location.href = "{{url('agent/record')}}"
    })
    // 推广二维码
    $("#Code").click(function () {
        window.location.href = "{{url("agent/qr")}}"
    })
    //切换统计图
    $(".selected").on("click", function () {
        $(".selected").removeClass('select')
        $(this).addClass('select')
    })

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    // 指定图表的配置项和数据

    var option1 = {
        xAxis: {
            type: 'category',
            data: {!! json_encode(array_keys($fans_orders)) !!}
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data:  {!! json_encode(array_values($fans_orders)) !!},
            type: 'line'
        }]
    };
    var option2 = {
        xAxis: {
            type: 'category',
            data: {!! json_encode(array_keys($fans_increase)) !!}
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            data:  {!! json_encode(array_values($fans_increase)) !!},
            type: 'line'
        }]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option1);

    $("#fans_order").click(function () {
        myChart.setOption(option1);
    })
    $("#fans_increase").click(function () {
        myChart.setOption(option2);
    })
</script>
</body>
</html>