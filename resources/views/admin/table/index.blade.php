<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--<title>jquery表格转excel表格插件</title>--}}

    {{--<link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">--}}
    {{--<link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">--}}

    {{--<link rel="stylesheet" type="text/css" href="/table/css/demo.css">--}}

</head>
<body>
<div class="box">
    <div class="box-header with-border">
        <div class="pull-right">
            <span onclick="collapse()" id="collapse"><i class="fa fa-minus"></i></span>
        </div>
        <span>
            <a class="btn btn-sm btn-primary grid-refresh" title="刷新"
               onclick="window.location.href=window.location.href"><i class="fa fa-refresh"></i><span
                        class="hidden-xs"> 刷新</span></a>

        </span>
        <button type="button" class="btn btn-sm btn-success grid-export" id="generate-excel"><i
                    class="fa fa-file-excel-o" aria-hidden="true"></i> 将表格导出为Excel
        </button>
    </div>

    <div class="box-body table-responsive no-padding" id="table-body">
        <table class="table table-hover table-bordered table-striped" id="test_table">
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
                            {!! $value !!}
                            {{--{{$value}}--}}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript" src="/table/external/jquery-1.8.2.js"></script>
<script type="text/javascript" src="/table/external/jszip.js"></script>
<script type="text/javascript" src="/table/external/FileSaver.js"></script>
<script type="text/javascript" src="/table/scripts/excel-gen.js"></script>
<script type="text/javascript" src="/table/scripts/demo.page.js"></script>
<script>
    function collapse() {
        var $t_body = $("#table-body");
        $t_body.toggleClass("hidden");
        if ($t_body.hasClass("hidden"))
            $("#collapse").html("<i class=\"fa fa-plus\"></i>");
        else
            $("#collapse").html("<i class=\"fa fa-minus\"></i>");
    }
</script>
</body>
</html>