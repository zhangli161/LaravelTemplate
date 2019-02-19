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



    <title>申请提现</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background: #f2f2f2;
        }

        p {
            margin-bottom: 0
        }

        .title {
            text-align: center;
            line-height: 60px;
            background: #00c0ef;
            color: #ffffff;
            font-size: 18px
        }

        .commission {
            line-height: 60px;
            font-size: 18px;
            padding-left: 10px;
            background: #fff;
        }

        .tx {
            margin-top: 1px;
            line-height: 70px;
            font-size: 18px;
            padding-left: 10px;
            background: #fff;
            color: #ff4000;
            font-size: 26px;
        }

        .user_id {
            margin-top: 1px;
            line-height: 70px;
            font-size: 18px;
            padding-left: 10px;
            background: #fff;
            font-size: 18px;
        }

        input {
            border: none;
            font-size: 24px;
            margin-left: 10px;
        }

        input,
        textarea,
        select,
        a:focus {
            outline: none;
        }

        input::-webkit-input-placeholder {
            color: #aab2bd;
            font-size: 18px;
        }

        .hint {
            margin-left: 10px;
            line-height: 40px;
            font-size: 14px;
            color: #666;
        }

        .but {

            background: #ff4000;
            color: #fff;
            width: 90%;
            line-height: 44px;
            border-radius: 6px;
            margin: 100px 5%;
            font-size: 18px;
        }


        .modal-dialog {
            margin: 2.4rem;
            top: 20%;
        }

        .modal-header {
            border-bottom: none
        }

        .confirm {
            background: #00c0ef;
            color: #fff;
        }

        .cancel {
            background: #ff3000;
            color: #fff;
        }

        .modal-footer {
            border-top: none
        }

        .user {
            margin-left: 10px;
            font-size: 18px;
        }
    </style>
</head>

<body>

<div>
    <p class="title">申请提现</p>
    <div class="commission">可提现佣金 : ￥{{round($agent->balance,2)}}</div>
    <div>
        <form method="POST" id="cash">
            {{csrf_field()}}
            <div class="user_id">小程序用户id :<input id="user_id" style="width:50%;font-size: 18px;" required="required"
                                                 type="text" name="" id="" placeholder="输入用户id"></div>
            <div class="user_id">关联手机号 :<input id="mobile" style="width:50%;font-size: 18px;" required="required"
                                               type="text" name="" id="" placeholder="输入关联手机号"></div>
            <div class="tx">￥<input style="width:60%;" type="number" name="payment" id="money" required="required"
                                    placeholder="输入提现金额"></div>
            <div class="hint">提示 : 提现金额不能小于1.00元</div>
        </form>
    </div>

    <input class="but" type="button" id="but" value="提交申请">
    <!-- <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        开始演示模态框
    </button> -->
</div>


<div class="container toast_div">
    <!-- 模态框 -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <!-- 模态框头部 -->
                <div class="modal-header">
                    <h5 class="modal-title">确认提现到该账户？</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- 模态框主体 -->
                <div class="modal-body">
                    <img id="avatar"
                         src="https://wx.qlogo.cn/mmopen/vi_32/ffuhpBJWUsA5McdUze3oHwBPZyG0OEDfOALPO5TZBGQ2NdnRQgsGYy8SMvqbznr2yPbG6td8DxV9nYxGaKbJeQ/132"
                         alt="" width="60" height="60">
                    <span class="user" id="user_name">测试账户</span>
                </div>
                <!-- 模态框底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn cancel" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn confirm" data-dismiss="modal" onclick="submitForm()">确认</button>

                </div>

            </div>
        </div>
    </div>

</div>
<!-- <script src="js/jquery-3.3.1.min.js"></script> -->
<script>
    var user = null;
    $("#but").click(function () {
        if ($("#user_id").val() == '') {

            showMessage('请填写小程序用户id！',3000,true);

        } else if ($("#money").val() == '') {
            showMessage('请输入提现金额！',3000,true);
        } else if ($("#mobile").val() == '') {
            showMessage('请输入关联手机号！',3000,true);
        } else if ($("#money").val() > {!!round($agent->balance, 2)!!}) {
            showMessage('提现金额不能超出可提现佣金！',3000,true);
        } else if ($("#money").val() <1) {
            showMessage('提现金额不能小于1元！',3000,true);
        }
        else {
            getUser();
            console.log('展开')
        }
        console.log('提交')
    })

    function getUser() {
        $.ajax({
            url: "{{url("agent/getUser")}}",
            data: {
                user_id: $("#user_id").val(),
                mobile: $("#mobile").val(),
            },
            success: function (res) {
                console.log(res);
                if (res.result) {
                    user = res.ret;
                    $("#avatar").attr("src", res.ret.avatar)
                    $("#user_name").html(res.ret.name)
                    $('#myModal').modal('show');
                } else {
                    alert("未找到用户！")
                }
            }

        })
    }

    function submitForm() {
        console.log("提交")
        if (!user) {
            alert("还没有选择用户！")
            return;
        }
        $.ajax({
            url: window.location.href,
            type: "POST",
            data: {
                _token: "{{csrf_token()}}",
                user_id: user.id,
                payment: $("#money").val(),

            },
            success: function (res) {
                console.log("表单提交返回", res)
                if (res.result){
                    showMessage('提交成功！',3000,true);
                    setTimeout(function (){
                        console.log("跳转")
                        window.location.href="{{url("/agent")}}";
                    },3500)
                } else {
                    showMessage('提交失败，请稍后重试！',3000,true);
                }
            }
        })
    }


</script>
</body>

</html>