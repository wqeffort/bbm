@extends('lib.admin.header')
@section('body')

<a onclick="onprint()" style="    background: #333;
    color: #FFF;
    padding: 1rem;
    text-align: center;
    line-height: 3rem;"><i class="fa fa-print"> 点击打印</i></a>
<div id="printArea" style="padding: 2rem;">
    <header style="border-bottom: 1px solid #666;padding-bottom: 2rem;">
        <ul style="display: flex;justify-content: space-around;">
            <li>
                <img style="width: 5rem;" src="{{ url('images/logo_b.png') }}">
            </li>
            <li>
                <dl>
                    <dt>梦享家装箱清单</dt>
                    <dd>订购日期: {{ $order['created_at'] }}</dd>
                    <dd>收货人姓名: {{ $order['name'] }}</dd>
                    <dd>收货人电话: {{ $order['phone'] }}</dd>
                    <dd>收货人地址: {{ $order['ads'] }}</dd>
                </dl>
            </li>
            <li>
                <div class="barcode">
                    <img style="height: 5rem;width: 17rem;" src="data:image/png;base64,{{ $order['barcode'] }}" alt="barcode"   />
                    <p>订单编号: {{ $order['num'] }}</p>
                </div>
            </li>
        </ul>
        <ol style="margin-top: .5rem;"><b>订单备注:</b>{{ $order['mark'] }}</ol>
    </header>
    <style type="text/css">
    dl{
        margin-top: 1rem;
    }
    dl dd {
        line-height: 1.5rem;
    }
    .barcode p {
        margin-top: 2rem;
    }
    tr {
        line-height: 3rem;
    }
</style>
    <table style="margin-top: 1rem;border-collapse: collapse;font-size: 13px;width: 100%; border-bottom: 1px solid #000;">
        <tr style="border-bottom: 1px solid #000;">
            <th class="tc">序 号</th>
            <th class="tc">商品名称</th>
            <th class="tc">购买数量</th>
            <th class="tc">商品属性</th>
            <th class="tc">
                @if ($order['type'] == '2')
                    积分(积分)
                @else
                    金额(元)
                @endif
            </th>
        </tr>
        @foreach ($order['data'] as $key => $element)
            <tr>
                <th class="tc">{{ $key+1 }}</th>
                <th class="tc">{{ $element->goods_name }}</th>
                <th class="tc">{{ $element->goods_num }}</th>
                <th class="tc">
                    @foreach ($element->attr as $value)
                        {{ $value }},
                    @endforeach
                </th>
                <th class="tc">
                    @if ($order['type'] == '2')
                        {{ $element->point }}
                    @else
                        {{ $element->price }}
                    @endif
                </th>
            </tr>
        @endforeach
    </table>
    <p style=" text-align: right;
    padding: 1rem;
">小计 : @if ($order['type'] == '2')
            {{ $order['totalPoint'] }} 积分
            @else
            {{ $order['totalPrice'] }} 元
        @endif</p>
</div>

<script type="text/javascript">
function PageSetup_Null() {
    try {
        var Wsh = new ActiveXObject("WScript.Shell");
        HKEY_Key = "header";
        //设置页眉（为空）   
        Wsh.RegWrite(HKEY_Root + HKEY_Path + HKEY_Key, "");
        HKEY_Key = "footer";
        //设置页脚（为空）   
        Wsh.RegWrite(HKEY_Root + HKEY_Path + HKEY_Key, "");
        HKEY_Key = "margin_bottom";
        //设置下页边距（0）   
        Wsh.RegWrite(HKEY_Root + HKEY_Path + HKEY_Key, "10");
        HKEY_Key = "margin_left";
        //设置左页边距（0）   
        Wsh.RegWrite(HKEY_Root + HKEY_Path + HKEY_Key, "10");
        HKEY_Key = "margin_right";
        //设置右页边距（0）   
        Wsh.RegWrite(HKEY_Root + HKEY_Path + HKEY_Key, "10");
        HKEY_Key = "margin_top";
        //设置上页边距（8）   
        Wsh.RegWrite(HKEY_Root + HKEY_Path + HKEY_Key, "8");
    } catch(e) {
        alert("请打开ActiveX控件");
    }
}

    function printHtml(html) {
        var bodyHtml = document.body.innerHTML;
        document.body.innerHTML = html;
        window.print();
        document.body.innerHTML = bodyHtml;
    }
    function onprint() {
        PageSetup_Null();
        var html = $("#printArea").html();
        printHtml(html);
    }
</script>
@endsection