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
<h3>待收货订单</h3>
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
                                    {{-- <p>积分: {{ $value->point }} 积分</p> --}}
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
                            <li>结算金额: <b>{{ $element->totalPoint }}</b> 积分</li>
                            {{-- <li>结算积分: <b>{{ intval($element->totalPoint * $element->sale) }}</b> 积分</li> --}}
                        </ul>
                    </dd>
                    <a href="javascript:;" onclick=getExpressInfo("{{ $element->orderNum }}")>点击查看物流信息 >></a>
                    <span class="orderBtn">已经发货</span>
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
// 请求接口获取物流信息
function getExpressInfo(num) {
    load();
    var height = '';
    var width = $(window).width();
    $.post('{{ url('express/info') }}/'+num+'', {
        "_token" : '{{ csrf_token() }}'
    }, function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.close(loadBox)
            console.log(obj.data)
            height = obj.data.length * 100;
            var nowInfo = obj.data.pop();
            console.log(nowInfo)
            var cell = '';
            $.each(obj.data, function(index, val) {
                cell += '<li>\
                    <h4>'+val.acceptTime+'<p>'+val.acceptAddress+'</p></h4>\
                    <div style="background: #FFBB42;float: left;margin-left: 35px;margin-top: -5px;width:'+width * 0.5+'px;padding: .5rem;color:#FFF;">\
                        <span>'+val.remark+'</span>\
                    </dl>\
                </li>'
            });
            var html = '\
            <div style="padding: .5rem;background: #ffedae;margin-bottom: 1rem;">\
                <span onclick="closeBtn()" style="position: absolute;right:.5rem;top: .7rem;z-index: 999;background: rgba(0,0,0,.7);color: #FFF;padding: .5rem;    border-radius: 50%;width: 1rem;height: 1rem;text-align: center;line-height: 1rem;">X</span>\
                <ul>\
                    <li>快件当前区域: <b>'+nowInfo.acceptAddress+'</b></li>\
                    <li>快件当前状态: '+nowInfo.remark+'</li>\
                </ul>\
            </div>\
            <div class="timeline">\
        <div class="timeline-date">\
            <ul>'+cell+'</ul>\
        </div>\
    </div>';
            var infoBox = layer.open({
                type: 1
                ,content: html
                ,anim: 'up'
                ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
            });
            $(function(){
                $(".timeline").eq(0).animate({
                    height: height
                },3000);
            });
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}

function closeBtn() {
    layer.closeAll();
}

</script>


@endsection






































