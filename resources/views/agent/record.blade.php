<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>提现记录</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background: #f2f2f2;
        }

        .title {
            position: fixed;
            top: 0;
            width: 100%;
            text-align: center;
            line-height: 60px;
            background: #00c0ef;
            color: #ffffff;
            font-size: 18px
        }

        .record {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            align-items: center;
            margin-bottom: 4px;
            background: #fff;

        }

        .state1 {
            color: #00CC33;
            width: 16%;
        }

        .state2 {
            color: #ff4000;
            width: 16%;
        }

        .state3 {
            color: #0099CC;
            width: 16%;

        }

    </style>
</head>
<body>
<p class="title">提现记录</p>
<div style=" width: 100%;height: 60px;"></div>
<div>
    @foreach($datas as $data)
        @if($data->status==1)
            <div class="record">
                <img width="30" height="30" src="/images/success.png" alt="">
                <div style=" line-height: 28px">
                    <div>提款金额：{{$data->amount/100}}</div>
                    <div>操作日期：{{$data->created_at}}</div>
                </div>
                <div class="state1">成功</div>
                <!-- <img width="10" height="16" src="images/jiantou.png" alt=""> -->
            </div>
        @elseif($data->status==2)
            <div class="record">
                <img width="30" height="30" src="/images/fail.png" alt="">
                <div style=" line-height: 28px">
                    <div>提款金额：￥{{$data->amount/100}}</div>
                    <div>操作日期：{{$data->created_at}}</div>
                </div>
                <div class="state2">失败</div>
                <!-- <img width="10" height="16" src="images/jiantou.png" alt=""> -->
            </div>
        @elseif($data->status==0)

            <div class="record">
                <img width="30" height="30" src="/images/wait.png" alt="">
                <div style=" line-height: 28px">
                    <div>提款金额：￥{{$data->amount/100}}</div>
                    <div>操作日期：{{$data->created_at}}</div>
                </div>
                <div class="state3">处理中</div>
                <!-- <img width="10" height="16" src="images/jiantou.png" alt=""> -->
            </div>
        @endif
    @endforeach
</div>
</body>
</html>