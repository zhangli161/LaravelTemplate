<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>推广二维码</title>
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

        .code {
            width: 60%;
            position: absolute;
            left: 20%;
            top: 30%;
        }
        .refresh{
            margin-top: 20px;
            background: #ddd;
            border: 1px solid #f2f2f2;
            width: 60%;
            height: 46px;
            margin-left: 20%;
            font-size: 16px;
            border-radius: 6px;
        }
        .save{
            margin-top: 10px;
            background: #00c0ef;
            border: none;
            width: 60%;
            margin-left: 20%;
            height: 46px;
            color: #fff;
            font-size: 16px;
            border-radius: 6px;
        }
        #qr_image{
            margin-top: 20px;
            margin-left: 10%;
        }
        button,textarea,select,a:focus {
            outline: none;
        }
    </style>
</head>

<body>

<div>
    <p class="title">推广二维码</p>
    <img id="qr_image" src=""/>
    <canvas id="qr" style="position:absolute;left: -9999px;top: -9999px;"></canvas>

    <button  class="refresh" onclick="window.location.href='{{url("/agent/qr/refresh")}}'" type="button" >刷新</button>
    {{--<button  class="save" onclick="exportCanvasAsPNG()" type="button" >保存</button>--}}
</div>
<script src="/js/jquery-3.3.1.min.js"></script>

<script>

    var c = document.getElementById("qr");
    var ctx = c.getContext("2d");

    // ctx.fill();
    var width = document.documentElement.clientWidth*0.8;
    var height = width + 40;
    c.width = width;
    c.height = height;
    ctx.fillStyle = "white";
    ctx.fillRect(0, 0,width, height);
    ctx.fill();
    //创建新的图片对象
    var img = new Image();

    // img.setAttribute('crossOrigin', 'anonymous');

    // img.setAttribute("crossOrigin", 'Anonymous')
    ctx.fillStyle = "black";
    ctx.font = "20px Arial";
    var str = "{{$agent->name}}";
    var str_width = ctx.measureText(str).width
    ctx.fillText(str, (width - str_width) / 2, width + 20);
    img.src = "{{$agent->xcx_qr}}";
    //浏览器加载图片完毕后再绘制图片
    img.onload = function () {


        //以Canvas画布上的坐标(10,10)为起始点，绘制图像
        ctx.drawImage(img, 0, 0, width, width);
        var ret=ctx.save();
        console.log(ret,ctx);
        var imgURL = c.toDataURL("image/png");
        // console.log(imgURL,$("#qr_image"))
        $("#qr_image").attr("src",imgURL)
    };


    function exportCanvasAsPNG() {

        var fileName = "未命名.png";
        var canvasElement = c;

        var MIME_TYPE = "image/png";

        var imgURL = canvasElement.toDataURL(MIME_TYPE);

        var dlLink = document.createElement('a');
        dlLink.download = fileName;
        dlLink.href = imgURL;
        dlLink.dataset.downloadurl = [MIME_TYPE, dlLink.download, dlLink.href].join(':');

        document.body.appendChild(dlLink);
        dlLink.click();
        document.body.removeChild(dlLink);
    }
</script>
</body>


</html>