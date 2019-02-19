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
    *{
        margin: 0;
        padding: 0;
    }
    body{
        background: #fff;
        color: #333;
        background-image: {{url("/images/background.jpg")}};
        background-repeat: no-repeat;
        background-size: 100% ;
    }
    .container{
        text-align: center
    }
    .form-group{
        display: flex;
        margin: 20px auto;
        width: 60%;
    }
    label{
        width: 16%;
        margin-right: 10px;
        font-size: 18px;
    }
    .btn{
        width: 60%;
    }
</style>
<body>

<div class="container">
    <h4 style=" margin: 160px auto 40px;text-align: center">供应商登录</h4>
    <form method="POST" action="{{ route('agent.login') }} " aria-label="{{ __('Login') }}">
        @csrf
        <div class="form-group">
            <!-- <label for="email">账号 : </label> -->
            <input type="text" class="form-control" id="name" name="name" placeholder="输入账号">
        </div>
        <div class="form-group">
            <!-- <label for="pwd">密码 : </label> -->
            <input type="password" class="form-control" id="pwd" name="password" placeholder="输入密码">
        </div>
        <!-- <div class="form-check">
          <label class="form-check-label">
            <input class="form-check-input" type="checkbox"> 记住账号
          </label>
        </div> -->
        @if($message)
        <div class="text-danger">{{$message}}</div>
        @endif
        <button type="submit" class="btn btn-primary">登录</button>
    </form>
</div>

</body>
</html>

