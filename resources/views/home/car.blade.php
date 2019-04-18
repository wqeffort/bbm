@extends('lib.home.header')
@section('body')
<style>
.car_goods_box input[type="checkbox"] {
    display: none;
}
label {
    position: absolute;
    right: 2rem;
    top: 4rem;
    color: #CCC;
    z-index: 999;
}
input[type="checkbox"]:checked + label {
    position: absolute;
    right: 2rem;
    top: 4rem;
    color: #C40000;
    z-index: 999;
}
</style>
{{-- 用户地址 --}}
<div class="ads_box">
    {{-- <ul>
        <input class="input" name="" id="city" type="text" placeholder="请选择" autocomplete="off" readonly="true"/><s></s>
    </ul>
 --}}

    <a href="{{ url('adsList') }}">
        <ul style="color:#FFF;">
            <li id="ads" style="overflow: hidden;">请选择收货地址</li>
            <li>></li>
        </ul>
    </a>
</div>
<div class="blank"></div>
@if (empty($goods))
    <script type="text/javascript">
        location.href = '{{ url('order/list') }}'
    </script>
@else
    <div class="car_box">
        <div class="car_goods_box">
            @foreach ($goods as $value)
                <ul>
                    <input type="checkbox" class="checkbox" id="car{{$value->id}}" name="car" value="{{ $value->id }}"  checked>
                    <label for="car{{$value->id}}" style="">
                        <i style="font-size: 1.5rem;" class="fa fa-check-circle"></i>
                    </label>
                    <li style="width: 35%;">
                        <img style="width: 100%;" src="{{ url($value->goods_pic) }}">
                    </li>
                    <li style="width: 63%;">
                        <dl>
                            <dt>{{ $value->goods_name }}</dt>
                            @if ($value->attrDepot)
                                <dd>售价: ¥ <b>{{ $value->goods_price + $value->attrPrice }}</b></dd>
                                <dd>积分: <b>{{ $value->goods_point + $value->attrPoint }}</b> 积分</dd>
                                <dd>数量: <b>{{ $value->goods_num }}</b> 件</dd>
                                <dd style="display: none;">
                                    @foreach ($value->onAttr as $element)
                                        <a href="javascript:;">{{ $element }}</a>
                                    @endforeach
                                </dd>
                                <input type="hidden" class="isOk" value="0">
                            @else
                            <p style="color:#C40000;">该商品需要从其他仓库进行调货,可能会延迟发货!</p>
                            <input type="hidden" class="isOk" value="1">
                            @endif

                        </dl>
                        <span onclick="del({{ $value->id }})"><i class="fa fa-trash-o"></i> 移除商品</span>
                    </li>
                </ul>
                {{-- 检查购物车中的商品是否还有库存 --}}
            @endforeach
        </div>
    </div>
@endif
<div class="blank" style="height: 10rem;"></div>
<div class="car_nav" style="z-index: 9999;">
    <ul>
        <li style="">
            <dl>
                <dd style="margin-top: .5rem;">应付:<span><b id="totalPrice"> {{ $totalPoint }} </b>CP</span></dd>
                <dd>实付:<span><b id="totalPoint"> {{ intval($totalPoint * $sale) }} </b>CP</span><b>({{ $sale * 10 }} 折)</b></dd>
            </dl>
        </li>
        <li style="line-height: 3rem">
            共计 <b id="totalNum">{{ $totalNum }}</b> 件商品
            <a href="javascript:;" onclick="goPay()">
                去结算
            </a>
        </li>
    </ul>
</div>

<div class="nav-bottom">
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('shop') }}">
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">服 务</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item false" ng-repeat="i in pages" href="{{ url('article') }}">
        <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">资 讯</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a class="nav-bottom-item true" ng-repeat="i in pages" href="{{ url('car') }}">
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

// $(function () {
//     alert('购物车正在维护,预计恢复时间为2018年8月31日下午3时');
// });



var isOk = 0;
//     $(function () {
//         $('.isOk').each(function(){
//            if($(this).val() == 1){
//                 isOk ++;
//            }
//        });
//         if (isOk) {
//             // layer.open({
//             //     content: '请注意,您的购物车中,个别商品已经没有库存,请删除后进行结算!'
//             //     ,btn: '我知道了'
//             // });
//             alert('请注意,您的购物车中,个别商品已经没有库存,请删除后进行结算!');
//         }
//     })


    $(function () {
        $.post('{{ url('car/getUserAds') }}',{
                        "_token" : '{{ csrf_token() }}'
                    },function (ret) {
                        var obj = $.parseJSON(ret);
                        console.log(obj);
                        if (obj.status == 'success') {
                            $('#ads').html('<h4 id="adsInfo">当前收货地址为默认收货地址</h4><p style="white-space: nowrap;color:#FFACAB;">'+obj.data+'</p>')
                        }else{
                            $('#ads').html('<h4 id="adsInfo">未获取到收货地址</h4><p style="white-space: nowrap; color:#FFACAB;">'+obj.data+'</p>')
                        }
                    })
     })
    // 移除商品
    function del(carId) {
        load();
        // 询问是否移除商品
        layer.open({
            content: '您确定要刷新一下本页面吗？'
            ,btn: ['移除商品', '考虑一下']
            ,yes: function(index){
                $.post('{{ url('car/setStatus') }}',{
                    "_token" : '{{ csrf_token() }}',
                    "carId" : carId
                },function (ret) {
                    layer.close(loadBox);
                    var obj = $.parseJSON(ret);
                    if (obj.status == 'success') {
                        //提示
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        location.reload();
                    }else{
                        //提示
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        location.reload();
                    }
                })
            }
        });
    }

    // 计算检查
    function goPay() {
        // 获取选中的商品
        var goods = $("input:checkbox[name='car']:checked").map(function(index,elem) {
            return $(elem).val();
        }).get().join(',');
        // console.log(goods)
        if (goods) {
            var adsInfo = $('#adsInfo').text();
            console.log(adsInfo);
            if (adsInfo == '未获取到收货地址' || !adsInfo) {
                layer.open({
                        content: '请先添加收货地址!'
                        ,btn: '我知道了'
                    });
            }else{
                if (!isOk) {
                    // 检查是否存在地址
                    location.href = '{{ url('car/pay') }}/'+goods;
                }else{
                    layer.open({
                        content: '请注意,该商品需要从其他仓库进行调货,可能会延迟发货!'
                        ,btn: '我知道了'
                    });
                }
            }
        }else{
            layer.open({
                    content: '请选择需要结算的商品!'
                    ,btn: '我知道了'
                });
        }
    }

    $(".checkbox").change(function() {
        var goods = $("input:checkbox[name='car']:checked").map(function(index,elem) {
                return $(elem).val();
            }).get().join(',');
        // console.log(goods);
        if (goods) {
            $.post('{{ url('car/total') }}/'+goods,{
                '_token' : '{{ csrf_token() }}'
            },function (ret) {
                var obj = $.parseJSON(ret)
                if (obj.status == 'success') {
                    $('#totalPrice').text(obj.data.totalPrice);
                    $('#totalPoint').text(obj.data.totalPoint);
                    $('#totalNum').text(obj.data.totalNum);
                }else{
                   layer.open({
                        content: '重新计算价格失败!请刷新页面!'
                        ,btn: '我知道了'
                    });
                }
            });
        }else{
            $('#totalPrice').text('0');
            $('#totalPoint').text('0');
            $('#totalNum').text('0');
        } 
    });
</script>


@endsection






































