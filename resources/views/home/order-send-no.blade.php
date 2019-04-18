@extends('lib.home.header')
@section('body')
<style type="text/css">
    h3 {
        border-left: 3px solid #C40000;
    margin: .5rem;
    padding-left: 1rem;
    }
    .orderList {
        padding: .5rem;
        color: #666;
    }
    .orderBox {
        padding: .5rem;
        border: 1px solid #EEE;
        border-top: 3px solid #C40000;
        position: relative;
        margin-bottom: 1.5rem;
    }
    dl dd {
        line-height: 1.5rem;
    }
    .payInfo {
        display: flex;
        justify-content: space-between;
    }
    ol {
        width: 100%;
    }
    ol ul {
        display: flex;
        justify-content: space-between;
        padding: .5rem;
        border-bottom: 1px solid #EEE;
    }
    ol ul li h5 {
        font-size: 1rem;
    }
    ol ul li img {
        width: 4rem;
        height: 4rem;
    }
    .payInfo li b {
        color:#C40000;
    }
    .orderBtn {
        position: absolute;
        top: .5rem;
        right: 1rem;
        padding: .1rem .3rem;
        background: #C40000;
        color: #FFF;
        font-size: .8rem;
        border-radius: .3rem;
    }
    dl dd a {
        background: #444;
        color: #FFF;
        padding: .2rem .5rem;
    }
</style>
<h3>待发货的订单</h3>
{{-- 订单列表 --}}
<div class="orderList">
    @if (empty($order))
    购物车空空如也,快去挑选商品吧
    @else
        @foreach ($order as $element)
            <div class="orderBox">
                <dl>
                    <dt>订单编号: {{ $element->orderNum }}</dt>
                    <dd>订单详情:</dd>
                    @foreach ($element as $value)
                        <ol>
                            <ul>
                                <li style="width: 30%;"><img src="{{ asset($value->goods_pic) }}"></li>
                                <li style="width: 70%;">
                                    <h5>{{ $value->goods_name }}</h5>
                                    <p>价格: {{ $value->point }} 积分</p>
                                    {{-- <p>实付: {{ $value->point * $sale }} 积分</p> --}}
                                    <span>数量: {{ $value->goods_num }} 件</span>
                                </li>
                            </ul>
                        </ol>
                    @endforeach
                    <dd>结算方式:

                    @switch($element->payType)
                        @case(0)
                            <b style="color:#C40000;">还未结算</b>
                            @break
                        @case(1)
                            <b style="color:#C40000;">微信支付</b>
                            @break
                        @case(2)
                            <b style="color:#C40000;">积分支付</b>
                            @break
                        @default
                            <b style="color:#C40000;">未知状态</b>
                    @endswitch
                    
                    </dd>
                    <dd>订单时间: {{ $element->created_at }}</dd>
                    <dd>
                        <ul class="payInfo">
                            <li>应付: <b>{{ $element->totalPoint }}</b> 积分</li>
                            {{-- <li>实付: <b>{{ intval($element->totalPoint * $element->sale) }}</b> 积分</li> --}}
                        </ul>
                    </dd>
                    {{-- <a href="">点击退货 >></a> --}}
                    <span class="orderBtn">准备发货</span>
                </dl>
            </div>
        @endforeach
    @endif
    
</div>
<div style="height: 5rem"></div>
<div class="nav-bottom" style="position: fixed;">
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('shop') }}">
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">主 页</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('article') }}">
        <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">资 讯</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('car') }}">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">购物车</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('user') }}">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>

<script type="text/javascript">
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






































