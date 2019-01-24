
<form  method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">

    <div class="box-body">

        <div class="fields-group">

            <div class="form-group  ">

                <label for="sence" class="col-sm-2  control-label">场景值</label>

                <div class="col-sm-8">


                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="sence" name="sence" value="{{$sence}}" class="form-control sence" placeholder="输入 场景值">


                    </div>


                </div>
            </div>
            <div class="form-group  ">

                <label for="page" class="col-sm-2  control-label">跳转页面</label>

                <div class="col-sm-8">


                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="page" name="page" value="{{$page}}" class="form-control page" placeholder="输入 跳转页面 不填默认为首页">


                    </div>

                    <span class="help-block">
    <i class="fa fa-info-circle"></i>&nbsp;不填默认为首页
</span>

                </div>
            </div>


        </div>

    </div>
    <!-- /.box-body -->

    <div class="box-footer">

        <input type="hidden" name="_token" value="DaJaBOcIOZrg8HhhquxqOCGmjm5vhkRqDfE6sJDo">

        <div class="col-md-2">
        </div>

        <div class="col-md-8">

            <div class="btn-group pull-right">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>

        </div>
    </div>
    {{csrf_field()}}

    {{--<input type="hidden" name="_previous_" value="http://localhost/admin" class="_previous_">--}}


    <!-- /.box-footer -->
</form>