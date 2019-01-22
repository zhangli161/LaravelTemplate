<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jquery表格转excel表格插件</title>

    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/table/css/demo.css">

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-3" style="padding:2em 0;">
            <button type="button" class="btn btn-success btn-block" id="generate-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> 将表格转换为Excel</button>
        </div>
        <div class="col-md-12" style="padding:2em 0;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="test_table">
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
                                    {{$value}}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/table/external/jquery-1.8.2.js"></script>
<script type="text/javascript" src="/table/external/jszip.js"></script>
<script type="text/javascript" src="/table/external/FileSaver.js"></script>
<script type="text/javascript" src="/table/scripts/excel-gen.js"></script>
<script type="text/javascript" src="/table/scripts/demo.page.js"></script>

</body>
</html>