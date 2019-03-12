<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | 商品</title>
    <link rel="stylesheet" href="{{url("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css")}}   ">
    <link rel="stylesheet" href="{{url("/vendor/laravel-admin/bootstrap-fileinput/css/fileinput.min.css?v=4.3.7")}}">
    <link rel="stylesheet"
          href="{{url("/vendor/laravel-admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css")}}">
    <link rel="stylesheet" href="{{url("vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css")}}">
    <link rel="stylesheet" href="{{url("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css")}}">
    <link rel="stylesheet" href="/css/goods.css">

    <script src="https://cdn.staticfile.org/vue/2.2.2/vue.min.js"></script>


    <!--[if lt IE 9]>
    <script type="text/javascript" src="/lib/html5shiv.js"></script>
    <script type="text/javascript" src="/lib/respond.min.js"></script>
    <![endif]-->
    {{--<link rel="stylesheet" type="text/css" href="/static/h-ui/css/H-ui.min.css"/>--}}
    <link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.min.css"/>
    <!--[if lt IE 9]>
    <link href="/static/h-ui/css/H-ui.ie.css" rel="stylesheet" type="text/css"/>
    <![endif]-->
    <!--[if IE 6]>
    <script type="text/javascript" src="/lib/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <style type="text/css">
        .ui-sortable .panel-header {
            cursor: move
        }

        .addImgs {
            width: 160px;
            height: 100px;
        }


    </style>

    {{--<script src="https://cdn.staticfile.org/vue/2.2.2/vue.min.js"></script>--}}

</head>
<body>
<div id="app" style="">

    <div class="panel panel-default">
        {{--<div class="panel-header">表单</div>--}}
        <div class="panel-body">

            {{--<h1>{{$spu->skus[1]}}</h1>--}}
            {{--<hr/>--}}
            {{--<p>@{{ JSON.stringify(spu.specs)}}</p>--}}

            {{--{{$spu->specs->pluck("id")}}--}}
            {{--<div v-for="sku in spu.skus" class=" margin">--}}
            {{--@{{ sku.sku_name }}--}}
            {{--@{{ sku.spec_value_ids }}--}}

            {{--</div>--}}
            <form action="{{url("/admin/goods")}}" method="post" class="form form-horizontal responsive" id="demoform">
                {{csrf_field()}}
                {{--<div class="form-group">--}}
                {{--<label class="control-label col-sm-2 ">商品编号：</label>--}}
                {{--<div class="formControls col-xs-8">--}}
                {{--<input type="number" class="input-number form-control" placeholder="商品编号" name="spu_no"--}}
                {{--id="spu_no"--}}
                {{--autocomplete="off" v-model="spu.spu_no">--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label class="control-label col-sm-2 ">商品名称：</label>
                    <div class="formControls col-xs-8">
                        <input type="text" class="input-text form-control" placeholder="商品名称" name="spu_name"
                               id="spu_name" v-model="spu.spu_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 ">商品描述：</label>
                    <div class="formControls col-xs-8">
                        <textarea cols="" rows="" class="textarea form-control" name="desc" id="desc"
                                  placeholder="" v-model="spu.desc"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 ">封面图片：</label>
                    <div class="formControls col-xs-8">
                        <div class="layui-upload">                
                            <div class="layui-upload-list">
                                <img class="layui-upload-img addImg" style="height: 300px;" :src="data.spu.thumb"
                                     id="demo1"/>
                            </div>
                            <button type="button" class="btn btn-primary pull-left margin" id="test1">选择</button>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2 ">上架状态：</label>
                    <div class="formControls col-xs-8">

                        <input type="checkbox" class="status la_checkbox"/>
                        <input type="hidden" class="status" name="status" id="status" v-model="spu.status"/>
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 ">商品分类：</label>
                    <div class="formControls col-xs-8">
                        <select class="form-control cate_id" style="width: 100%;" name="cate_id" id="cate_id"
                                v-model="spu.cate_id">

                            @foreach($cates=\App\Models\Category::where('parentid', '1')->get()->pluck('id','name') as $name=>$id)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>


                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 ">场景分类：</label>
                    <div class="formControls col-xs-8">
                        @foreach($cates=\App\Models\Category::where('parentid', '2')->get()->pluck('id','name') as $name=>$id)
                            <label class='input_style checkbox_bg'>
                                <input id="sence{{$id}}" type="checkbox" name="sence[]" v-model="spu.sence_ids"
                                       value="{{$id}}">
                                {{$name}}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 ">图文详情：</label>
                    <div class="formControls col-xs-8">
                        <textarea id="detail_content" name="detail[content]" v-model="spu.detail.content">@{{ spu.detail.content }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2 ">规格：</label>
                    <div class="formControls col-xs-8">
                        {{--<div class="form-control " style="width: 100%">--}}
                        {{--<span class="control-label" v-for="spec in spu.specs">@{{ spec.spec_name }}</span>--}}
                        {{--</div>--}}

                        <select id="add-spec-select" multiple class="form-control "
                                style="display: inline;width: 100%;"
                                v-model="spu.spec_ids"
                        >
                            {{--<option value=""></option>--}}
                            {{--<option v-for="spec in specs" v-show="spu.spec_ids.indexOf(spec.id)==-1"--}}
                            {{--value="@{{spec.id}}">@{{spec.spec_name}}--}}
                            {{--</option>--}}
                            @foreach($specs as $spec)
                                <option
                                        value="{{$spec->id}}">{{$spec->spec_name}}
                                </option>
                            @endforeach
                        </select>
                        {{--<input id="addSpec" class="btn radius btn-primary pull-right  margin"--}}
                        {{--type="button" value="添加"/>--}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2 ">子类商品：</label>
                    <div class="formControls col-xs-8">
                        <input id="addSKU" class="btn radius btn-primary pull-right  margin"
                               type="button" value="添加SKU">
                        <table class="table table-bordered table-hover">
                            <tr class="text-danger">
                                <th class="text-c">子商品名称</th>
                                {{--<th class="text-c">规格</th>--}}
                                @foreach($specs as $spec)
                                    {{--@{{spu.spec_ids.indexOf('1')>=0}}--}}
                                    <th class="text-c"
                                        v-if="array_in(spu.spec_ids,{{$spec->id}})">{{$spec->spec_name}}</th>
                                    {{--<div class="form-group" v-if="spu.spec_ids.indexOf('{{$spec->id}}')>=0">--}}
                                @endforeach
                                <th class="text-c">价格</th>
                                <th class="text-c">库存</th>
                                <th class="text-c">操作</th>
                            </tr>
                            <tr class="text-c" v-for="sku in spu.skus">
                                <td class="text-c">@{{ sku.sku_name }}</td>
                                @foreach($specs as $spec)
                                    <td class="text-c" v-if="array_in(spu.spec_ids,{{$spec->id}})">
                                        @foreach($spec->values as $value)
                                            <label class="label label-primary"
                                                   v-if="(sku.spec_value_ids)[{{$spec->id}}]=={{$value->id}}">{{$value->value}} </label>
                                        @endforeach
                                    </td>
                                @endforeach
                                <td class="text-c">@{{ sku.price }}</td>
                                <td class="text-c">@{{ sku.stock }}</td>
                                <td class="text-c">
                                    <span class="btn btn-danger btn-sm" v-on:click="deleteSKU(sku)">
                                        删除
                                    </span>
                                    <span class="btn btn-primary btn-sm" v-on:click="editSKU(sku)">
                                        编辑
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-xs-8 col-xs-offset-2">
                        <div class="btn-group pull-right">
                            <div class="btn btn-primary" onclick="submitSPU()">提交</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div id="modal-global" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content radius ">
                <div class="modal-header">
                    <h3 class="modal-title">SKU</h3>
                    <a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
                </div>
                <div class="modal-body ">
                    <!-- 模态框头部 -->
                    {{--@{{ JSON.stringify(editingSKU) }}--}}
                    <form class="form form-horizontal responsive">


                        <div class="form-group">
                            <span class="control-label col-sm-2 ">子商品编号</span>
                            <div class="formControls col-xs-8">
                                <input type="number" class="form-control" placeholder="" id="sku_no"
                                       v-model="editingSKU.sku_no">
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="control-label col-sm-2 ">子商品名称</span>
                            <div class="formControls col-xs-8">
                                <input type="text" class="form-control" placeholder="" id="sku_name"
                                       v-model="editingSKU.sku_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="control-label col-sm-2 ">价格</span>
                            <div class="formControls col-xs-8">
                                <input type="number" step="0.01" class="form-control" placeholder="" id="price"
                                       v-model="editingSKU.price">
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="control-label col-sm-2 ">库存</span>
                            <div class="formControls col-xs-8">
                                <input type="number" class="form-control" placeholder="" id="stock"
                                       v-model="editingSKU.stock">
                            </div>
                        </div>

                        <div class="form-group">
                            <span class="control-label col-sm-2 ">减库存时间</span>

                            <div class="formControls col-xs-8">
                                <lable>
                                    <i class='input_style radio_bg'><input type="radio" name="hot" id="stock_type"
                                                                           v-model="editingSKU.stock_type"
                                                                           value="0"></i>
                                    付款减库存
                                </lable>
                                <lable>
                                    <i class='input_style radio_bg'><input type="radio" name="hot" id="stock_type"
                                                                           v-model="editingSKU.stock_type"
                                                                           value="1"></i>
                                    下单减库存
                                </lable>
                            </div>
                        </div>

                        <div class="category form-group">
                            <label class="control-label col-sm-2 " for="sel1">是否包邮</label>
                            <div class="formControls col-sm-8 ">
                                {{--<input type="checkbox" class="postage la_checkbox"/>--}}
                                <input type="checkbox" class="postage" id="postage"/>
                            </div>
                        </div>

                        {{--<div class="form-group">--}}
                        {{--<span class="control-label col-sm-2 ">排序</span>--}}
                        {{--<input type="text" class="form-control" placeholder="">--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <span class="control-label col-sm-2 ">搜索关键词</span>
                            <div class="formControls col-sm-8 ">
                                <select class="form-control search_word_search_words_" style="width: 100%;"
                                        multiple="multiple" data-placeholder="输入 搜索关键词" id="searchwords-select"
                                >
                                    {{--<option value="editingSKU.sku_name" selected>aaa</option>--}}
                                    {{--<option v-for="word in editingSKU.search_word.search_words">@{{ word }}</option>--}}
                                    {{--<option v-for="word in editingSKU.search_word.search_words"  selected>@{{ word }}</option>--}}
                                </select>
                                <input type="hidden" id="search_word" v-model="editingSKU.search_word.search_words"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="control-label col-sm-2 ">商品规格</div>
                            <div class="formControls col-sm-8 ">
                                @foreach($specs as $spec)
                                    {{--@{{spu.spec_ids.indexOf('1')>=0}}--}}
                                    <div class="form-group" v-if="array_in(spu.spec_ids,{{$spec->id}})">
                                        <label class="control-label col-sm-1 pull-left ">{{$spec->spec_name}}：</label>
                                        <div class="formControls col-xs-11">
                                            <select class="form-control" style="display: inline;width: 100%;"
                                                    id="spec_value_ids-{{$spec->id}}"
                                                    v-model="editingSKU.spec_value_ids[{{$spec->id}}]"
                                            >
                                                {{--<option value=""></option>--}}
                                                @foreach($spec->values as $value)
                                                    <option value="{{$value->id}}">{{$value->value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="control-label col-sm-2 ">商品图片</span>
                            <div class="formControls col-xs-8">
                            <!-- <input type="file" multiple class="sku_thumb" id="sku_albums"
                                       {{--v-bind="albums"--}}
                            {{--data-initial-preview="['{{$spu->thumb}}']"--}}
                                    data-show-caption="true"
{{--data-initial-caption="{{basename($spu->thumb)}}"--}}
                                    > -->
                                <!-- 选择图片 -->
                                <div class="layui-upload">
                                    <button type="button" class="layui-btn" id="test2">产品轮播图上传(可多选)</button>
                                    <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                        预览图：
                                        <div class="layui-upload-list row" id="demo2">
                                            <div style='padding: 20px;' v-for='(item,ind) in editingSKU.thumbs'
                                                 :key='ind' class="col-xs-4">
                                                <img
                                                        style='width: 100%; height: auto;' :src="item"
                                                        class="layui-upload-img addImgs">
                                                <i @click='delImgClick(ind)'
                                                   class='btn btn-danger pull-right margin'>删除</i>
                                            </div>

                                        </div>
                                    </blockquote>
                                </div>
                            </div>
                            <div class="col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2 ">配套产品：</label>
                            <div class="formControls col-xs-8">

                                {{--<input type="hidden" name="cate_id" v-model="spu.cate_id"/>--}}

                                <select class="form-control matched_skus" style="width: 100%;" multiple="multiple"
                                        name="matched_skus[]" v-model="editingSKU.matched_sku_ids">
                                    <option value=""></option>
                                    @foreach($skus=\App\Models\GoodsSKU::all()->pluck('id','sku_name') as $name=>$id)
                                        <option value="{{$id}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--@{{ JSON.stringify(editingSKU.matched_sku_ids) }}--}}

                        <div class="form-group">
                            <label class="control-label col-sm-2 ">相似产品：</label>
                            <div class="formControls col-xs-8">

                                {{--<input type="hidden" name="cate_id" v-model="spu.cate_id"/>--}}

                                <select class="form-control similar_skus" style="width: 100%;" multiple="multiple"
                                        name="similar_sku_ids[]" v-model="editingSKU.similar_sku_ids"
                                >
                                    {{--<option value=""></option>--}}
                                    @foreach($skus=\App\Models\GoodsSKU::all()->pluck('id','sku_name') as $name=>$id)
                                        <option value="{{$id}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--@{{ JSON.stringify(editingSKU.similar_sku_ids) }}--}}

                    </form>
                </div>


            {{--<div class="category form-group">--}}
            {{--<label class="control-label col-sm-2 " for="sel1">是否包邮</label>--}}
            {{--<div class="formControls col-sm-8 ">--}}
            {{--嗷嗷嗷--}}
            {{--</div>--}}
            {{--</div>--}}

            <!-- 模态框底部 -->
                <div class="modal-footer">
                    {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>--}}
                    <button type="button" class="btn btn-primary" v-on:click="SKU_submit()">确认</button>
                </div>
            </div>


            {{--<div class="modal-footer">--}}
            {{--<button class="btn btn-primary">确定</button>--}}
            {{--<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>--}}
            {{--</div>--}}
        </div>
    </div>
</div>
</div>

<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/jquery-ui/1.9.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/layui/layui.all.js"></script>
<link rel="stylesheet" href="/layui/css/layui.css" media="all">
{{--<script type="text/javascript" src="/static/h-ui/js/H-ui.js"></script>--}}
<script src="{{url("vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<script type="text/javascript" src="/lib/jquery.SuperSlide/2.1.1/jquery.SuperSlide.min.js"></script>
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/messages_zh.min.js"></script>
<script src="{{url("vendor/laravel-admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js?v=4.3.7")}}"></script>
<script src="{{url("vendor/laravel-admin/bootstrap-fileinput/js/fileinput.min.js?v=4.3.7")}}"></script>
<script src="{{url("vendor/laravel-admin/bootstrap-switch/dist/js/bootstrap-switch.min.js")}}"></script>
<script src="{{url("vendor/ueditor/ueditor.config.js")}}"></script>
<script src="{{url("vendor/ueditor/ueditor.all.js")}}"></script>
<script src="{{url("vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js")}}"></script>
<style type="text/css">

    .addImgs {
        width: 160px;
        height: 100px;
    }


</style>
<script>
    const newSKU = {
        sku_no: '',
        sku_name: '',
        order: '',
        postage: '1',
        price: '',
        spu_id: '',
        stock: "0",
        stock_type: "0",
        spec_value_ids: [],
        search_word: {
            search_words: []
        },
        albums: [],
        matched_sku_ids: [],
        similar_sku_ids: [],
        thumbs: []
    }

    var data = {
        editingSKU: newSKU,
        albums: ['{{$spu->thumb}}']
    };


    function putSKUToModal(sku) {
        data.editingSKU = sku;
        data.editingSKU.spec_value_ids = {};
        console.log("编辑", sku);
        $("#searchwords-select").empty()
        for (var i in sku.search_word.search_words) {
            $("#searchwords-select").append("<option value=\"" + sku.search_word.search_words[i]
                + "\" selected>" + sku.search_word.search_words[i] + "</option>");
        }
        initAlbums()
        // $(".matched_skus");
        // $(".similar_skus").val(sku.similar_sku_ids);
        //配套产品
        $(".matched_skus").val(sku.matched_sku_ids).select2({
            "multiple": true,
            "allowClear": true,
            "placeholder": {"id": "", "text": ""}
        }).on("change", function (e) {
            console.log("matched_skus", $(this).val())
            data.editingSKU.matched_sku_ids = $(this).val();
        });
        //相似产品
        $(".similar_skus").val(sku.similar_sku_ids).select2({
            "multiple": true,
            "allowClear": true,
            "placeholder": {"id": "", "text": ""}
        }).on("change", function (e) {
            console.log("similar_skus值发生改变", $(this).val())
            data.editingSKU.similar_sku_ids = $(this).val();
        });
        $('#postage').bootstrapSwitch('state', (data.editingSKU.postage == '1' || data.editingSKU.postage == 1));
        $("#modal-global").modal("show")
    };
    $(function () {

        var spu = {!! $spu->toJson() !!};
        // console.log("规格测试", spu.spec_ids,spu.spec_ids.indexOf("1"),1=="1")
        if (!spu.detail) {
            spu.detail = {content: ""}
        }
        if (!isURL(spu.thumb))
            spu.thumb = "{{\Illuminate\Support\Facades\Storage::disk('admin')->url("/")}}" + spu.thumb;

        var specs ={!! $specs->toJson() !!};
        data.spu = spu;
        data.specs = specs;
        var new_spu_id = -1;


        console.log(spu.thumb)
        console.log('data', data)
        var vm = new Vue({
            el: '#app',
            data: data,
            methods: {
                deleteSKU: function (sku) {
                    for (var i in spu.skus) {
                        if (spu.skus[i].id == sku.id) {
                            spu.skus.splice(i, 1);
                            return;
                        }
                    }
                },
                editSKU: function (sku) {
                    console.log('sku', sku)
                    for (var i in data.spu.skus) {
                        if (data.spu.skus[i].id == sku.id) {
                            putSKUToModal(spu.skus[i])
                        }
                    }
                },
                delImgClick: function (ind) {
                    data.editingSKU.thumbs.splice(ind, 1)
                },
                SKU_submit: function () {
                    console.log("编辑完成", data.editingSKU, $("#sku_albums").val());

                    var require_fileds = ['sku_no', 'sku_name', 'price', 'stock', 'stock_type']
                    for (var i in require_fileds) {
                        if (isEmpty(data.editingSKU[require_fileds[i]] + "")) {
                            console.log("filed " + require_fileds[i] + "", data.editingSKU[require_fileds[i]])
                            $("#" + require_fileds[i]).focus()
                            return;
                        }
                    }
                    if (isEmpty(data.editingSKU.search_word.search_words)) {
                        data.editingSKU.search_word.search_words = [data.editingSKU.sku_name]
                    }

                    if (isEmpty(data.editingSKU.thumbs)) {
                        alert('请上传商品图片');
                        return;
                    }
                    console.log("规格", data.spu.spec_ids, data.editingSKU.spec_value_ids,)

                    for (var i in data.spu.spec_ids) {

                        console.log("规格", data.editingSKU.spec_value_ids[data.spu.spec_ids[i]])
                        if (isEmpty(data.editingSKU.spec_value_ids[data.spu.spec_ids[i]])) {
                            $("#spec_value_ids-" + data.spu.spec_ids[i]).focus()
                            return;
                        }
                    }


                    $("#modal-global").modal("hide")
                    for (var i in data.spu.skus) {
                        if (data.spu.skus[i].id == data.editingSKU.id) {
                            console.log("编辑完成", data.spu.skus[i].sku_name)
                            return
                        }
                    }
                    data.spu.skus.push(data.editingSKU)


                }
            },
            watch: {
                this: function () {
                    console.log('监听data改变', this);
                }
            }

        })

        $("#addSKU").on('click', function () {
            putSKUToModal(newSKU)
        });


        $("input.thumb").fileinput({
            "language": 'zh',
            "uploadUrl": '/admin/upload', //上传的地址
            "overwriteInitial": true,
            "initialPreviewAsData": true,
            "browseLabel": "\u6d4f\u89c8",
            "showRemove": false,
            "showUpload": false,
            "deleteExtraData": {
                "thumb": "_file_del_",
                "_file_del_": "",
                "_token": "iu5l6nSoclwY25Ygw4MsH6rNOwQVBf4lG2Zlojak",
                "_method": "PUT"
            },
            "deleteUrl": "http:\/\/www.calex-china.com\/admin\/",
            "allowedFileTypes": ["image"]
        });

        $('.status.la_checkbox').bootstrapSwitch({
            size: 'small',
            state: (data.spu.status == "1"),
            width: 80,
            onText: '上架',
            offText: '下架',
            onColor: 'primary',
            offColor: 'default',
            onSwitchChange: function (event, state) {
                console.log("开关", state)
                data.spu.status = state ? '1' : '0';
                // $(event.target).closest('.bootstrap-switch').next().val(state ? '1' : '0').change();
            }
        });

        $('#postage').bootstrapSwitch({
            size: 'small',
            state: (data.editingSKU.postage == '1' || data.editingSKU.postage == 1),
            onText: '是',
            offText: '否',
            onColor: 'primary',
            offColor: 'default',
            onSwitchChange: function (event, state) {
                console.log("开关情况", state, data.editingSKU.postage == '1')
                data.editingSKU.postage = (state ? '1' : '0')
                // $(event.target).closest('.bootstrap-switch').next().val(state ? '1' : '0').change();
            }
        });

        $("#add-spec-select")
            .select2({
                "multiple": true,
                "allowClear": true,
                "placeholder": {"id": "", "text": ""},

            })
            .on("change", function (e) {
                console.log(data.spu.spec_ids, "add-spec-select", $(this).val(), typeof (data.spu.spec_ids))
                data.spu.spec_ids = $(this).val();
                data.spu.specs = specs.filter(function (item) {
                    // console.log("item", item, item.id, data.spu.spec_ids.indexOf("" + item.id))
                    return array_in(data.spu.spec_ids, item.id);
                });
                console.log("规格", specs, data.spu.specs)
            });


        $(".cate_id").select2({"allowClear": true, "placeholder": {"id": "", "text": ""}})
            .on("change", function (e) {
                console.log("cate_id", $(this).val())
                data.spu.cate_id = $(this).val();
            });


        window.UEDITOR_CONFIG.serverUrl = '/ueditor/server';
        UE.delEditor("detail_content");

        var ue_detail_content = UE.getEditor('detail_content', {"initialFrameHeight": 400});
        console.log("aaa")
        ue_detail_content.ready(function () {
            console.log("Editor is ready")
            ue_detail_content.execCommand('serverparam', '_token', 'oO5LqwGRVaMfvwNy5aTnRsgZ48AOXJccvpksnbNP');
        });
        ue_detail_content.addListener("contentChange", function (e, detail) {
            var content = ue_detail_content.getContent();
            data.spu.detail.content = content;
            console.log("UE内容变更", e, content)
        })

        $(".search_word_search_words_").select2({
            tags: true,
            tokenSeparators: [',']
        });

        $("#searchwords-select").on("change", function () {
            console.log("搜索词", $(this).val())
            data.editingSKU.search_word.search_words = $(this).val();
            $("#search_word").val($(this).val())
        });

        layui.use('upload', function () {
            var $ = layui.jquery
                , upload = layui.upload;

            //普通图片上传
            var uploadInst = upload.render({
                elem: '#test1',
                url: '/admin/upload',
                field: 'file_data',
                data: {
                    _token: "{{csrf_token()}}",
                    _method: "PUT"
                }
// ,auto: false
//,multiple: true
// ,bindAction: '#test9'
                , before: function (obj) {
//预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
//$('#demo1').attr('src', result); //图片链接（base64）
                    });
                }
                , done: function (res) {
//如果上传失败
                    console.log("res", res)
                    if (!res.result) {
                        layer.msg('上传失败');
                    }
                    if (res.result) {
                        data.spu.thumb = res.ret;
                    }
//上传成功
                }
                , error: function () {
//演示失败状态，并实现重传
                    var demoText = $('#demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a >');
                    demoText.find('.demo-reload').on('click', function () {
                        uploadInst.upload();
                    });
                }
            });

            //多图片上传
            upload.render({
                elem: '#test2'
                , url: '/admin/upload'
                , field: 'file_data'
                , data: {
                    _token: "{{csrf_token()}}",
                    _method: "PUT"
                }
                , multiple: true
                // ,auto: false
                // ,bindAction: '#test9'
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        // $('#demo2').append('')
                    });
                }
                , done: function (res) {
                    //上传完毕
                    console.log(res)
                    if (res.result) {
                        // data.editingSKU.albums.push({url: res.ret});

                        data.editingSKU.thumbs.push(res.ret);
                    }
                }
            });

        });

    });


    function initAlbums() {
        if (!data.editingSKU.thumbs) {
            var albs = data.editingSKU.albums.map(function (alb) {
                if (isURL(alb.url))
                    return alb.url;
                else
                    return "{{\Illuminate\Support\Facades\Storage::disk('admin')->url("/")}}" + alb.url;
            })
            var albums = [];

            data.editingSKU.thumbs = albs;
            console.log("初始值", albs)
        } else {
            for (var i in data.editingSKU.thumbs) {
                if (!isURL(data.editingSKU.thumbs[i]))
                    data.editingSKU.thumbs[i] = "{{\Illuminate\Support\Facades\Storage::disk('admin')->url("/")}}" + data.editingSKU.thumbs[i];

            }
        }

        $("input.sku_thumb").fileinput('destroy').fileinput({
            "language": 'zh',
            "uploadUrl": '/admin/upload', //上传的地址
            'uploadExtraData': {
                _token: "{{csrf_token()}}",
                "_method": "PUT"
            },
            "overwriteInitial": true,
            "initialPreviewAsData": true,
            "browseLabel": "\u6d4f\u89c8",
            "showRemove": false,
            "showUpload": false,
            "maxFileCount": 10,//上传最大的文件数量
            "showBrowse": true,
            "browseOnZoneClick": true,
            {{--"deleteExtraData": {--}}
                    {{--"thumb": "_file_del_",--}}
                    {{--"_file_del_": "",--}}
                    {{--_token:"{{csrf_token()}}",--}}
                    {{--"_method": "PUT"--}}
                    {{--},--}}
                    {{--"deleteUrl": "http:\/\/www.calex-china.com\/admin\/",--}}
            "allowedFileTypes": ["image"],
            'initialPreview': albs,
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            }
        }).on('filepreupload', function (event, data, previewId, index) {     //上传中
            var form = data.frm, files = data.files, extra = data.extra,
                response = data.response, reader = data.reader;
            console.log('文件正在上传');
        }).on("fileuploaded", function (event, data_r, previewId, index) {    //一个文件上传成功
            console.log('文件上传成功！' + data_r.id, event, data, previewId, index);
            if (data_r.response.result) {
                data.editingSKU.albums.push({id: previewId, url: data_r.response.ret});

            }
        }).on('fileerror', function (event, data, msg) {  //一个文件上传失败
            console.log('文件上传失败！' + data.id);
        }).on('filebatchselected', function (event, data_1, msg) {//选择文件后处理事件
            console.log('文件选择完毕', event, data_1, msg)
            data.editingSKU.albums = [];
            $(this).fileinput("upload")
        }).on('filedeleted', function (event, key) {
            console.log('已删除', enent, key);
        }).on('filepredelete', function (event, key) {
            console.log('预删除 ', event, key);
        });
        console.log(data)
    }

    function isURL(str_url) {// 验证url
        var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
        // + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" // ftp的user@
        // + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
        // + "|" // 允许IP和DOMAIN（域名）
        // + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.
        // + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名
        // + "[a-z]{2,6})" // first level domain- .com or .museum
        // + "(:[0-9]{1,4})?" // 端口- :80
        // + "((/?)|" // a slash isn't required if there is no file name
        // + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
        var re = new RegExp(strRegex);
        return re.test(str_url);
    }

    function isEmpty(obj) {
        if (typeof obj == "undefined" || obj == null || obj == "" || obj == []) {
            return true;
        } else {
            return false;
        }
    }

    function submitSPU() {
        console.log(data);
        var require_fileds = ['spu_name', 'desc', 'thumb', 'cate_id']
        for (var i in require_fileds) {
            // console.log("filed " + require_fileds[i] + "", data.spu[require_fileds[i]])

            if (isEmpty(data.spu[require_fileds[i]])) {
                console.log("filed " + require_fileds[i] + "", data.spu[require_fileds[i]])
                $("#" + require_fileds[i]).focus()
                alert("请完整填写")
                return;
            }
        }
        if (isEmpty(data.spu.detail.content)) {
            alert("请填写图文详情")
        }

        var post_data = data.spu;
        post_data._token = "{{csrf_token()}}";
        post_data.status = post_data.status ? "1" : "0"
        $.post("{{url("/admin/goods")}}", post_data, function (res) {
            console.log("提交返回", res);
            if (res.result) {
                window.location.href = "{{url('admin/goods')}}"
            } else {
                alert("")
            }
        })
    }

    function array_in(array, item) {
        // console.log("查找",array,item)
        for (var i in array) {
            if (array[i] == item)
                return true;
        }
        // console.log("不存在")
        return false;
    }

</script>
</body>
</html>