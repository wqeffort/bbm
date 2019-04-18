@extends('lib.view.header')
@section('body')
<style type="text/css">
body {
    background: #EEE;
}
.vm_nav span {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 1rem;
}
.vm_cart_cont {
    margin: .5rem;
}
.vm_cart_cont ul li {
    background: #FFF;
    border-radius: .5rem;
    padding: .5rem;
    margin-bottom: 1rem;
}
.vm_cart_cont input[type="radio"] {
    display: none;
}
.vm_cart_cont input[type="checkbox"] {
    display: none;
}
input[type="checkbox"]:checked + label {
    color: #000;
}
input[type="radio"]:checked + label {
}
.vm_cart_cont label {
    margin-top: 2.5rem;
    color: #999;
    padding: .5rem;
    font-size: 1.5rem;
}
.nv_b label {
    color: #999;
}
.item_cont {
    display: flex;
    justify-content: flex-start;
    margin-top: .5rem;
}
.sum_nav {
    position: fixed;
    width: 100%;
    background: #FFF;
    bottom: 0rem;

}
.nav_b {
    font-size: 1.2rem;
}
.nv_b input[type="radio"] {
    display: none;
}
.nv_b input[type="checkbox"] {
    display: none;
}
.fund {
            position: fixed;
            z-index: 999;
            width: 2rem;
            height: 2rem;
            background: #6395ed;
            opacity: .8;
            text-align: center;
            line-height: 2rem;
            bottom: 8rem;
            left: 0;
        }
    .fund a {
        color: #FFF;
        font-size: 1.5rem;
    }
</style>


<div class="vm_cart_cont">
    <ul>
        @foreach ($goods as $value)
        <li id="list{{ $value->id }}">
            <div class="item">
                <ol style="border-bottom: 1px solid #EEE;text-align: right;color: #999">{{ $value->created_at }}</ol>
            </div>
            <div class="item_cont">
                <input id="{{ $value->id }}" type="checkbox" name="goods" checked="">
                <label for="{{ $value->id }}"><i class="fa fa-check-circle"></i></label>
                <div style="/*width: 45%;*/ height: auto;margin-right: .5rem;">
                    <img style="width: 7rem;height: 7rem;" src="http://jaclub.shareshenghuo.com/{{ $value->goods_pic }}">
                </div>
                <div class="item_desc">
                    <h3 style="font-size:1rem;">{{ $value->goods_name }}</h3>
                    @foreach ($value->onAttr as $element)
                    <h5>{{ $element }}</h5>
                    @endforeach
                    <p><b>{{ $value->total }}Cp</b> <span>¥{{ $value->total }}</span></p>
                </div>
            </div>
            <div style="font-size: 1.2rem;height: 1.5rem;line-height: 2rem;display: flex;justify-content: space-between;">
                <p style="font-size: 1rem;color: #999;" onclick="remove({{ $value->id }},{{ $value->total }})"><i class="fa fa-trash"></i> 移除商品</p>
                <p style="text-align: right;">
                    <i onclick="down({{ $value->id }},{{ $value->total }})" class="fa fa-minus-square">
                    </i><input style="height: 2rem;line-height: 2rem;width: 3rem;text-align: center;padding: 0;margin: 0;border: 0;font-size: 1.5rem;" type="text" name="num{{ $value->id }}" disabled="disabled" value="{{ $value->goods_num }}"/><i onclick="up({{ $value->id }},{{ $value->total }})" class="fa fa-plus-square"></i>
                </p>
            </div>
        </li>
        @endforeach
    </ul>
</div>

<div class="sum_nav">
    <ul style="display: flex;justify-content: space-between;padding: .5rem;line-height: 2rem;">
        <li class="nv_b">
            <input id="all" type="checkbox" name="" checked="">
            <label style="font-size:1.2rem;" for="all" onclick="allChecked()"><i class="fa fa-check-circle"></i> 全选</label>
        </li>
        <li style="font-size: 1rem;color: #666;">合计:<span id="total_point">{{ $totalPoint }}</span>Cp or ¥<span id="total_price">{{ $totalPrice }}</span></li>
        <li style="padding: 0 1rem;background: #6495ed;color: #FFF;font-size: 1rem;" onclick="sub()">结算</li>
    </ul>
</div>

<div style="height: 10rem;"></div>

<script type="text/javascript">
    // 数量加减操作
    function up(id,price) {
        var num = $('input[name=num'+id+']').val();
        var newNum = parseInt(num) + 1;
        $('input[name=num'+id+']').val(newNum);
        // 获取到下方总价
        var ago_point = $('#total_point').html();
        var ago_price = $('#total_price').html();
        $('#total_point').html(parseInt(ago_point) + parseInt(price));
        $('#total_price').html(parseInt(ago_price) + parseInt(price));

    }
    function down(id,price) {
        var num = $('input[name=num'+id+']').val();
        var newNum = parseInt(num) - 1;
        if (newNum < 1) {
            layer.open({
                content: '购买的数量不能为0'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            $('input[name=num'+id+']').val(newNum);
            // 获取到下方总价
            var ago_point = $('#total_point').html();
            var ago_price = $('#total_price').html();
            $('#total_point').html(parseInt(ago_point) - parseInt(price));
            $('#total_price').html(parseInt(ago_price) - parseInt(price));
        }
    }

    function allChecked() {
        if ($("#all").prop("checked")) {
            // console.log(1)
            $(':checkbox[name=goods]').each(function() {
                $(this).prop('checked', false);
            });
            // $('input:checked').prop('checked',true);
        }else{
            // console.log(2)
            $(':checkbox[name=goods]').each(function() {
                $(this).prop('checked', true);
            });
        }

    }

    // 移除购物车商品
    function remove(id,price) {
        layer.open({
            content: '您确定要移除该商品吗?'
            ,btn: ['移除', '取消']
            ,skin: 'footer'
            ,yes: function(index){
                load();
                $.post('{{ url('view/cart/remove') }}/'+id, {
                    "_token" : "{{ csrf_token() }}",
                }, function (ret) {
                    var obj = $.parseJSON(ret);
                    layer.close(loadBox);
                    if (obj.status == 'success') {
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        // 获取当前总价
                        var agoTotal = $('#total_point').html();
                        var newPrice = parseInt(agoTotal) - parseInt($("input[name=num"+id+"]").val()) * parseInt(price);
                        // console.log(newPrice)
                        $("#list"+id).remove();
                        // 改变价格
                        $('#total_point').html(newPrice);
                        $('#total_price').html(newPrice);
                    }else{
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                })
            }
        });
    }

    function sub() {
        // 获取选中的商品和数量
        var array = [];
        $.each($('input[name=goods]:checked'), function(index, val) {
            var goods = []
            goods.push(val.id)
            goods.push($('input[name=num'+val.id+']').val())
            array[index] = goods;
        });
        console.log(array)
        if (array.length == 0) {
            layer.open({
                content: '请先添加商品进购物车再结算'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            load();
            // 生成订单
            $.post('{{ url('view/order/make') }}', {
                "_token" : '{{ csrf_token() }}',
                "data" : array
            }, function(ret) {
                var obj = $.parseJSON(ret);
                layer.close(loadBox);
                console.log(obj);
                if (obj.status == 'success') {
                    console.log(obj.data);
                    location.href="{{ url('view/order/set') }}/"+obj.data
                }else{
                    layer.open({
                        content: obj.msg
                        ,btn: '我知道了'
                    });
                }
            });
        }
    }
</script>
@endsection
