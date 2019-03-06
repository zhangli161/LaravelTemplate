<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.12.5/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        background: #000;
        color: #333;
        background-image: url(" /images/Login_bg.jpg ");
        background-repeat: no-repeat;
        background-size: 100%;
    }

    .container {
        text-align: center
    }

    .form-group {
        display: flex;
        margin: 20px auto;
        width: 60%;
    }

    label {
        width: 16%;
        margin-right: 10px;
        font-size: 18px;
    }

    .btn {
        width: 80%;
        border: none;
        background: #ff3000;
        margin-top: 20px;
        border-radius: 16px;
        padding: 8px 0;
        border: 1px solid #ff3000;
    }

    input {
        background: none;
        outline: none;
        border: 0px;
        color: #fff;
        margin-left: 6px;
    }

    .user {
        display: flex;
        width: 80%;
        margin: auto;
        padding: 4px 10px;
        border: 1.4px solid #fff;
        border-radius: 18px;
        margin-top: 20px;
        align-items: center;
    }

    .password {
        display: flex;
        width: 80%;
        margin: auto;
        padding: 4px 10px;
        border: 1.4px solid #fff;
        border-radius: 18px;
        margin-top: 16px;
        align-items: center;
        position: relative
    }

    .visible {
        position: absolute;
        right: 10px;

    }
</style>
<body>

<div class="container">
    <img style="margin: 22% auto 0;" src="/images/Login_logo.png" alt="" width="180" height="180">
    <p style=" margin:3% auto 25%;text-align: center;color:#fff; font-size: 14px;">欢迎登陆凯莱克斯商城</p>
    <form method="POST" action="{{ route('agent.login') }} " aria-label="{{ __('Login') }}">
        {{csrf_field()}}
        <div class="user">
            <img src="/images/user.png" alt="" srcset="" width="30" height="30">
            <input type="text" id="username" placeholder="用户名" name="name">
        </div>
        <div class="password">
            <img src="/images/password.png" alt="" srcset="" width="30" height="30">
            <input type="password" id="password" placeholder="密码" name="password">
            <img class="visible" src="/images/Invisible.png" alt="" srcset="" width="30" height="30">
        </div>
        @if(isset($message))<div class="text-danger">{{$message}}</div>@endif
        <button type="submit" class="btn btn-primary">登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
    </form>
</div>


<script>
    $(function () {
        $(".visible").click(function () {
            if ($("#password").attr("type") == "password") {
                $(this).attr("src", "/images/visible.png")
                $("#password").attr("type", "text")
            } else {
                $(this).attr("src", "/images/Invisible.png")
                $("#password").attr("type", "password")
            }
        })
    })
</script>
</body>
</html>