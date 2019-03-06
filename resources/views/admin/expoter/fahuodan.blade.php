<table cellpadding="0" cellspacing="0" width="867">
    <colgroup>
        <col />
        <col />
        <col />
        <col />
        <col />
        <col/>
        <col />
    </colgroup>
    <tbody>
    <tr height="88" style=";height:88px" class="firstRow">
        <td colspan="8" height="88" width="867" style="" valign="top" align="center">
            {{--<span style=";position:absolute;z-index:1;margin-left:16px;margin-top:17px;width:154px;height:54px"><br/>--}}
            {{--</span> &nbsp; --}}
            <span style="font-weight: bold;font-size: 30">凯莱克斯商城</span>
        </td>
    </tr>
    <tr height="22" style="height:22px">
        <td colspan="2" height="22" style="">
            订单发生日期：&nbsp;{{$order->created_at}}
        </td>
        <td colspan="2" style="border-right-width: 1px;border-right-color: black;border-left: none">
            订单号：{{$order->id}}
        </td>
        <td colspan="4" style="border-left:none">
            买家ID：{{$order->user_id}}
        </td>
    </tr>
    <tr height="25" style=";height:25px">
        <td colspan="2" height="25" style="">
            收货人姓名：{{$order->receiver_name}}
        </td>
        <td colspan="2" style="border-right-width: 1px;border-right-color: black;border-left: none">
            收货人电话：{{$order->receiver_phone}}
        </td>
        <td colspan="4" style="border-left:none">
            收货人地址：{{$order->receiver_address}}
        </td>
    </tr>
    <tr height="25" style=";height:25px">
        <td colspan="8" height="25" style=""></td>
    </tr>
    <tr height="20" style="height:20px">
        <td height="20" style="border-top: none;">
            序列号
        </td>
        <td style="border-top:none;border-left:none">
            型号
        </td>
        <td style="border-top:none;border-left:none">
            商品名称
        </td>
        <td style="border-top:none;border-left:none">
            规格
        </td>
        <td style="border-top:none;border-left:none">
            数量
        </td>
        <td style="border-top:none;border-left:none">
            单位
        </td>
        <td style="border-top:none;border-left:none">
            实付金额
        </td>
        <td style="border-top:none;border-left:none">
            备注
        </td>
    </tr>
    @foreach($order->skus as $number=>$order_sku)
    <tr height="26" style=";height:26px">
        <td height="26" style="border-top: none;">
            {{$number+1}}
        </td>
        <td style="border-top:none;border-left:none">
            {{$order_sku->sku->sku_no}}
        </td>
        <td style="border-top:none;border-left:none">
            {{$order_sku->sku_name}}
        </td>
        <td style="border-top:none;border-left:none">
            {{implode('  ',$order_sku->sku->spec_value_strs)}}
        </td>
        <td style="border-top:none;border-left:none">
            {{$order_sku->amount}}
        </td>
        <td style="border-top:none">
            只
        </td>
        <td style="border-top:none;border-left:none">
            {{$order_sku->total_price}}
        </td>
        <td style="border-top:none;border-left:none">

        </td>
    </tr>
    @endforeach

    <tr height="29" style=";height:29px">
        <td height="29" style="border-top: none;">
            订单统计：&nbsp;
        </td>
        <td style="border-top:none"></td>
        <td style="border-top:none"></td>
        <td style="border-top:none"></td>
        <td style="border-top:none">{{$order->skus->sum('amount')}}</td>
        <td style="border-top:none;border-left:none">
            只
        </td>
        <td style="border-top:none;border-left:none">{{$order->payment}}</td>
        <td style="border-top:none;border-left:none"></td>
    </tr>
    <tr><td colspan="8">备注: {{$order->note?$order->note:"无"}}</td></tr>
    </tbody>
</table>