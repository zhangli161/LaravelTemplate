<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>财务流水</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .title {
            position: fixed;
            top: 0;
            display: inline-block;
            text-align: center;
            width: 100%;
            line-height: 60px;
            background: #00c0ef;
            color: #ffffff;
            font-size: 18px;

        }

        .record {
            display: flex;
            justify-content: space-between;
            width: 90%;
            margin: auto;
            align-items: center;
            border-bottom: 1px solid #f2f2f2
        }

        .remarks {
            font-size: 18px;
            font-weight: 600;
            line-height: 40px;
        }

        .time {
            line-height: 30px;
            font-size: 16px;
            color: #666;
        }

        .state0 {
            font-size: 20px;
        }

        .state1 {
            font-size: 20px;
            color: #00c0ef;
        }
    </style>
</head>
<body>

<div>
    <p class="title">财务流水</p>
</div>
<div style="width:100%;height:60px"></div>

@foreach($agent->finances as $finance)
    @if($finance->income<$finance->expenditure)
        <div class="record">
            <div class="Info">
                <div class="remarks">{{$finance->note?$finance->note:"支出"}}</div>
                <div class="time">{{$finance->created_at}}</div>
            </div>
            <div class="state0"><span>-</span>{{$finance->expenditure-$finance->income}}</div>
        </div>
    @else
        <div class="record">
            <div class="Info">
                <div class="remarks">{{$finance->note?$finance->note:"收入"}}</div>
                <div class="time">{{$finance->created_at}}</div>
            </div>
            <div class="state1"><span>+</span>{{$finance->income-$finance->expenditure}}</div>
        </div>
    @endif
@endforeach
</body>
</html>