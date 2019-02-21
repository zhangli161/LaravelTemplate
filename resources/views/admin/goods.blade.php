<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | 商品</title>
    <script src="https://cdn.staticfile.org/vue/2.2.2/vue.min.js"></script>

</head>
<body>
<div id="app" style="">
    <div>
        <p>spu_name</p>
        <input v-model="spu_name" placeholder="编辑我……">
        <p>消息是: @{{ spu_name }}</p>
    </div>
    <div>
        <p>detail.content</p>
        <input v-model="detail.content" placeholder="编辑我……">
        <p>消息是: @{{ detail.content }}</p>
    </div>
    <div v-for="sku in skus">
        <p>sku.sku_name</p>
        <input v-model="sku.sku_name" placeholder="编辑我……">
        <p>消息是: @{{ sku.sku_name }}</p>
    </div>
    <button onclick="addSKU()">添加SKU</button>
</div>

<script>
    var data = {!! $spu->toJson() !!}
    console.log(data, data.skus[0])
    new Vue({
        el: '#app',
        data: data
    })

    function addSKU() {
        data.skus.push({
            sku_no: '',
            sku_name: '',
            order: '',
            postage: '',
            price: '',
            spu_id: '',
            stock:"",
            stock_type:""
        });
    }
</script>
</body>
</html>