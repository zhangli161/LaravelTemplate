<head>
    <title>{{isset($title)?$title:"表格"}}</title>
    <meta charset='utf-8' />
</head>
<div class="box">
    <div class="box-header with-border">
        <div class="pull-right">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a id="export"class="btn btn-sm btn-twitter" title="导出"><i class="fa fa-download"></i><span class="hidden-xs"> 导出</span></a>
                {{--<button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown" aria-expanded="false">--}}
                    {{--<span class="caret"></span>--}}
                    {{--<span class="sr-only">Toggle Dropdown</span>--}}
                {{--</button>--}}
                {{--<ul class="dropdown-menu" role="menu">--}}
                    {{--<li><a href="/admin/users?_pjax=%23pjax-container&amp;_export_=all" target="_blank">全部</a></li>--}}
                    {{--<li><a href="/admin/users?_pjax=%23pjax-container&amp;_export_=page%3A1" target="_blank">当前页</a></li>--}}
                    {{--<li><a href="/admin/users?_pjax=%23pjax-container&amp;_export_=selected%3A__rows__" target="_blank" class="export-selected">选择的行</a></li>--}}
                {{--</ul>--}}
            </div>
        </div>
        <span>
            <a class="btn btn-sm btn-primary grid-refresh" title="刷新" onclick="window.location.href=window.location.href"><i class="fa fa-refresh"></i><span
                        class="hidden-xs"> 刷新</span></a>
        </span>
    </div>

    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="form">
            <thead>
            <tr>
                @foreach($titles as $title)
                    <th>{{$title}}</th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $title=>$value)
                        <td>
                            {{--{{$value}}--}}
                            {!! $value !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</div>
<script>

    // 使用outerHTML属性获取整个table元素的HTML代码（包括<table>标签），然后包装成一个完整的HTML文档，设置charset为urf-8以防止中文乱码
    var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementById("form").outerHTML + "</body></html>";
    // 实例化一个Blob对象，其构造函数的第一个参数是包含文件内容的数组，第二个参数是包含文件类型属性的对象
    var blob = new Blob([html], { type: "application/vnd.ms-excel" });
    var a = document.getElementById("export");
    // console.log(html);
    // 利用URL.createObjectURL()方法为a元素生成blob URL
    a.href = URL.createObjectURL(blob);
    // 设置文件名
    a.download = "导出文件.xls";
</script>