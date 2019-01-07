<form method="POST" action="" class="form-horizontal" accept-charset="UTF-8" pjax-container="1">
    <div class="box-body fields-group">
        <div class="form-group  ">
            <label for="title" class="col-sm-2  control-label">提现金额</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-terminal fa-fw"></i></span>
                    <input style="width: 130px; text-align: right;" type="text" id="payment" name="payment" value=""
                           class="form-control payment" placeholder="单位元">
                </div>
            </div>
        </div>

        <div class="form-group  ">
            <label for="user_id" class="col-sm-2  control-label">小程序用户id</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                    <input type="text" id="user_id" name="user_id" value="" class="form-control user_id" placeholder="输入用户id">
                </div>
            </div>
        </div>
        {{csrf_field()}}
    </div>


    <!-- /.box-body -->
    <div class="box-footer">
        <div class="col-md-2"></div>

        <div class="btn-group pull-right">
            <button type="submit" class="btn btn-info pull-right">提交</button>
        </div>
    </div>
    </div>
</form>