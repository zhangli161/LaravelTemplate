<style>

    #go_top {
        position: fixed;
        LEFT: 85%;
        bottom: 50px;
    }

</style>
<div id="go_top">
<i class="fa fa-arrow-up fa-th-large" style="font-size: 20px;border: black 2px solid;border-radius: 2px;padding: 5px">返回顶部</i>

    {{--<img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1550204928909&di=f564e24ea6d27b8c4d171606f1f53c9b&imgtype=0&src=http%3A%2F%2Fpic.51yuansu.com%2Fpic3%2Fcover%2F00%2F98%2F75%2F58ddb2857d01d_610.jpg"--}}
    {{--alt="回到顶部图片"--}}
    {{--style="width:50px">--}}

</div>
<script>

    $(document).ready(function () {

        $("#go_top").hide();



            $(window).scroll(function () {

                if ($(window).scrollTop() > 0) {

                    $("#go_top").fadeIn(500);

                } else {

                    $("#go_top").fadeOut(500);

                }

            });

            $("#go_top").click(function () {

                $('body,html').animate({scrollTop: 0}, 100);

                return false;

            });


    });

</script>
