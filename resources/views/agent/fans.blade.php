<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>我的粉丝</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style> .title1 {
            /*position: fixed;*/
            top: 0;
            display: inline-block;
            text-align: center;
            width: 100%;
            line-height: 60px;
            background: #00c0ef;
            color: #ffffff;
            font-size: 18px;

        }</style>
</head>
<body>
<div>
    <p class="title1">我的粉丝</p>
</div>
{{--<div style="width:100%;height:60px"></div>--}}


<div class="row">
    @for($i=0;$i<6;$i++)
        @foreach($data as $fans)
            {{--        {!! json_encode($fans) !!}--}}
            <div class="col-2">
                <div style="">
                    @if(is_null($fans->avatar))
                        <div>头像丢失</div>
                    @else
                        <img src="{{$fans->avatar}}" style="width: 100%">
                    @endif
                </div>
                <div style="font-size:10px;text-align: center">
                    {{$fans->name}}
                </div>
                {{--<div style="text-align: center">--}}
                    {{--加入时间：{{$fans->bind_agent_time}}--}}
                {{--</div>--}}
            </div>

        @endforeach
    @endfor
</div>
<div class="pull-right">
    {!! $data->links() !!}
</div>
</body>
</html>