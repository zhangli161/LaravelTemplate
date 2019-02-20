<!DOCTYPE html>
<html>
<head>
    <title>代理商信息</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.12.5/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>


    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background: #f7f8f9;
        }

        p {
            margin-bottom: 0;
        }

        .table td, .table th {
            padding: .75rem;
            /* vertical-align: top; */
            border-top: 0px solid #dee2e6;
        }

        .title {
            text-align: center;
            line-height: 60px;
            background: #00c0ef;
            color: #ffffff;
            font-size: 18px
        }

        .but {
            text-align: center;
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .sign_out {
            background: #00c0ef;
            border: none;
            color: #fff;
            padding: 6px 30px;
            border-radius: 6px;
        }

        .modify {
            background: #FF3366;
            border: none;
            color: #fff;
            padding: 6px 30px;
            border-radius: 6px;
        }

        input,
        textarea,
        select,
        a:focus {
            outline: none;
        }

    </style>
</head>
<body>

<div class="">
    <p class="title">代理商信息详情列表</p>
    <table style="width:100%;text-align: center;border-top: 0px solid #ffffff" class="table table-striped">
        <thead></thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{$row['name']}}</td>
                <td>{{$row['value']}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

<div class="but">
    <a href="{{url("agent/logout")}}"><input class="sign_out" type="button" value="退出登录"></a>
    <input class="modify" type="button" value="修改密码">
</div>

<script>
    $(function () {

        $("input").eq(1).click(function () {
            window.location.href = "{{url("agent/change_password")}}"
        })
    })
</script>
</body>
</html>