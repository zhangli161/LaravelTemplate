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
    </style>
</head>

<body>

<div>
    <p class="title">推广二维码</p>
    <canvas id="qr"></canvas>

    <button onclick="" type="button" style="width: 50%;height: 50px">刷新</button>
    <button onclick="exportCanvasAsPNG()" type="button" style="width: 50%;height: 50px">保存</button>
</div>
<script>

    var c = document.getElementById("qr");
    var ctx = c.getContext("2d");

    var width = document.documentElement.clientWidth;
    var height = width + 20;
    c.width = width;
    c.height = height;

    //创建新的图片对象
    var img = new Image();

    img.setAttribute('crossOrigin', 'anonymous');

    // img.setAttribute("crossOrigin", 'Anonymous')
    ctx.font = "20px Arial";
    var str = "hahahfsdafsadfsdfsdfaha";
    var str_width = ctx.measureText(str).width
    ctx.fillText(str, (width - str_width) / 2, width + 20);
    img.src = "/storage/agentQR/Agent_1_1547104500.jpg";
    //浏览器加载图片完毕后再绘制图片
    img.onload = function () {


        //以Canvas画布上的坐标(10,10)为起始点，绘制图像
        ctx.drawImage(img, 0, 0, width, width);
        var ret=ctx.save();
        console.log(ret,ctx)
    };


    function exportCanvasAsPNG() {

        var fileName = "未命名";
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