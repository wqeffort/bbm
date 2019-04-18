@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #EEE;
    }
    h3 {
        text-align: center;
        font-size: 1.2rem;
        padding-top: .5rem;
            background: #C40000;
        color: #FFF;
        margin: 1rem;
        margin-bottom: 0;
    }
    .payBox {
        border: 1px solid #C40000;
        margin: 1rem;
        padding: .5rem;
        border-top: 10px solid #C40000;
        background: #FFF;
        margin-top: 0;
    }
    .payBox dl dt {
        text-align: center;
        font-size: 1rem;
        margin-bottom: .5rem;
    }
    .payBox dl dd ul li {
        line-height: 2rem;
    }
    hr {
        margin: .8rem 0;
    }
    input[type="radio"]:checked + label {
        border: 2px solid #C40000;
        padding: .5rem 1rem;
        color: #000;
        font-weight: bold;
        margin: 0 1rem;
    }
    input[type="radio"] + label {
        border: 1px solid #EEE;
        padding: .5rem 1rem;
        color: #000;
        font-weight: bold;
        margin: 0 1rem;
    }
</style>
<h3>订单支付</h3>
<div class="payBox">
    <dl>
        <dd>
            <ul>
                <li>订单编号: <b>{{ $num }}</b></li>
                <li>商品件数: <b style="color:#C40000;">{{ $totalNum }}</b> 件</li>
                <li>应付积分: <b style="color:#C40000;">{{ $totalPoint }}</b> 积分</li>
                <li>实付积分: <b style="color:#C40000">{{ $totalPoint * $sale }}</b> 积分</li>
            </ul>
        </dd>
        <hr>
        <dd>
            <ul>
                <li>收 货 人: <b>@if ($ads)
                    {{$ads->name}}
                @else

                @endif</b></li>
                <li>联系电话: <b>@if ($ads)
                    {{$ads->phone}}
                @else

                @endif</b></li>
                <li>收货地址: <b>@if ($ads)
                    {{$ads->province.$ads->city.$ads->area.$ads->ads}}
                @else
                @endif</b></li>
            </ul>
        </dd>
        <hr>
        <dd>
            <ul>
                <li>优惠信息: <b>暂未获取到优惠信息</b></li>
            </ul>
        </dd>
        <hr>
        <dd>
            <ul>
                <li>订单备注:</li>
                <li><textarea style="width: 94%;height: 5rem;" id="mark" >未填写收货信息,请在订单备注中补充收货信息</textarea></li>
            </ul>
        </dd>
        <hr>
        <dd>
            <ul>
                <li style="padding-bottom: .5rem;">请选择支付方式:</li>
                <li style="padding-bottom: .5rem;">
                    <span>
                        <input type="radio" name="payType" value="price" id="wechat" style="display: none;">
                        <label for="wechat">
                            <i class="fa fa-wechat"></i> 微信支付
                        </label>
                    </span>
                    <span>
                        <input type="radio" name="payType" value="point" id="point" checked="checked" style="display: none;">
                        <label for="point">
                            <i class="fa fa-yelp"></i> 积分抵扣
                        </label>
                    </span></li>
            </ul>
        </dd>
    </dl>
</div>
<div onclick="pay()" style="margin: 1rem;
    height: 2.5rem;
    line-height: 2.5rem;
    text-align: center;
    background: #C40000;
    color: #FFF;
    font-size: 1rem;"><span>现在支付</span></div>
<script type="text/javascript">
function pay() {
    load();
    var payType = $('input:checked').val();
    if (payType == 'point') {
        // 检查积分情况,是否够支付
        $.get('{{ url('getUserPoint') }}',{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                console.log(obj.data);
                if (obj.data < {{ $totalPoint * $sale }}) {
                    layer.close(loadBox);
                    layer.open({
                                content: '您的积分不足以抵扣商品积分,请充值后再进行结算'
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                }else{
                    // 发起积分抵扣
                    var mark = $("#mark").val();
                    $.post('{{ url('pay/pointPayment') }}/{{ $num }}', {
                        "_token" : '{{ csrf_token() }}',
                        "mark" : mark
                    }, function(ret) {
                        layer.close(loadBox);
                        var obj = $.parseJSON(ret);
                        if (obj.status == 'success') {
                            layer.open({
                                content: obj.msg,
                                btn: '我知道了',
                                shadeClose: false,
                                yes: function(){
                                    // 成功后的回调,刷新
                                    location.href='{{ url('car') }}'
                                }
                            });
                        }else{
                            layer.open({
                                content: obj.msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                        }
                    });
                }
            }else{
                layer.close(loadBox);
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
    }else{
        location.href="{{ url('payment') }}/{{ $num }}";
    }
}
// 提交添加地址的表单
function addAds() {
    var name = $('input[name=name]').val();
    var phone = $('input[name=phone]').val();
    var city = $('input[name=city]').val();
    var ads = $('input[name=ads]').val();
    $.post('{{ url('addAds') }}', {
        "_token" : '{{ csrf_token() }}',
        "name" : name,
        "phone" : phone,
        "city" : city,
        "ads" : ads
    }, function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            location.href = '{{ url('adsList') }}';
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}

</script>


@endsection






































