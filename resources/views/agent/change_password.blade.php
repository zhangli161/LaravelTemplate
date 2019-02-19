<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/toast.css">

    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.12.5/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script src="/js/toast.js"></script>

    <title>修改密码</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .title {
            text-align: center;
            line-height: 60px;
            background: #00c0ef;
            color: #ffffff;
            font-size: 18px
        }

        /* .form-group{
        display: flex;
    }
    .form-control{
        width: 60%;
    } */
        .btn-primary {
            margin-top: 60px;
            width: 90%;
            margin-left: 5%;
            background: #00c0ef;
        }
    </style>
</head>

<body>

<div>
    <p class="title">修改密码</p>
</div>

<!-- <div>
   新密码：<input type="password" name="" id=""><br>
   确认密码：<input type="password" name="" id="">
</div> -->


<div class="container">
    <form id="form-password" method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label for="email">新密码:</label>
            <input type="password" class="form-control" id="new" placeholder="" name="password">
        </div>
        <div class="form-group">
            <label for="pwd">确认密码:</label>
            <input type="password" class="form-control" id="pwd" placeholder="">
        </div>
        <button type="button" class="btn btn-primary">修改</button>
    </form>
</div>


<script>
    $(function () {
        $(".btn").click(function () {
            if ($("#new").val() == '') {
                showMessage('请填写新密码！', 3000, true);
            } else if ($("#new").val().length < 5) {
                showMessage('密码不能少于6位！', 3000, true);
            } else if ($("#pwd").val() == '') {
                showMessage('请填写确认密码！', 3000, true);
            } else if ($("#new").val() == $("#pwd").val()) {
                $("#form-password").submit();
            } else {
                showMessage('前后密码填写不一致！', 3000, true);
            }
        })
    })
</script>
</body>

</html>