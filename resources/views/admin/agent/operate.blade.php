@foreach($datas as $data)
<div class="small-box bg-{{isset($data["color"])?$data["color"]:"aqua"}}">
    <div class="inner">
        <h3>{{isset($data["number"])?$data["number"]:""}}</h3>

        <p>{{isset($data["text"])?$data["text"]:""}}</p>
    </div>
    @if(isset($data["url"]))
        <a class="icon" href="{{$data["url"]}}">
            <i class="fa {{isset($data["icon"])?$data["icon"]:"fa-arrow-circle-right"}}"></i>
        </a>
    @else
        <div class="icon">
            <i class="fa {{isset($data["icon"])?$data["icon"]:"fa-list-alt"}}"></i>
        </div>
    @endif
    {{--<a href="#" class="small-box-footer" target="_blank">--}}
    {{--More info <i class="fa fa-arrow-circle-right"></i>--}}
    {{--</a>--}}
</div>
@endforeach

{{--<div class="info-box">--}}
    {{--<span class="info-box-icon bg-{{isset($color)?$color:"aqua"}}">--}}
        {{--<i class="fa {{isset($icon)?$icon:"fa-list-alt"}}"></i></span>--}}


    {{--<!-- /.info-box-content -->--}}
    {{--@if(isset($url))--}}
        {{--<a href="#" class="info-box-content" target="_blank">--}}
            {{--<span class="info-box-text">{{isset($text)?$text:""}}</span>--}}
            {{--<span class="info-box-number">{{isset($number)?$number:""}}</span>--}}
        {{--</a>--}}
    {{--@else--}}
        {{--<div class="info-box-content">--}}
            {{--<span class="info-box-text">{{isset($text)?$text:""}}</span>--}}
            {{--<span class="info-box-number">{{isset($number)?$number:""}}</span>--}}
        {{--</div>--}}
    {{--@endif--}}
{{--</div>--}}