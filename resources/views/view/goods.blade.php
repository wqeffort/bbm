@extends('lib.view.header')
@section('body')
<style type="text/css">
.fund {
    position: fixed;
    z-index: 999;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #FFF;
    opacity: .8;
    text-align: center;
    line-height: 2rem;
    top: 1rem;
    left: 1rem;
}
.fund a {
    color: #000;
    font-size: 1.5rem;
}

.goods_cont {
    padding: .5rem 1rem;
    background: #FFF;
    position: relative;
}
.goods_cont h3 {
    font-size: 1.5rem
}
.goods_cont span {
    color: #666;
    font-size: 1rem;
}
.goods_cont p {
    font-size: 1.2rem;
    color: #6495ed;
}

.goods_cont div {
    position: absolute;
    right: 1rem;
    bottom: .5rem;
    color: #999;
}
.vm_goods_attr {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    font-size: 1.2rem;
}
.vm_goods_attr i {
    font-size: 1.5rem;
}
.vm_goods_attr_info input[type="radio"] {
    display: none;
}
input[type="radio"]:checked + label {
    border: 1px solid #6495ed;
    padding: .2rem .5rem;
    color: #6495ed;
    border-radius: .3rem;
    background: #EEE;
}
.vm_goods_attr_info label {
    border: 1px solid #999;
    padding: .2rem .5rem;
    color: #000;
    border-radius: .3rem;
    background: #EEE;
    font-size: 1.2rem;
}
.vm_goods_attr_info ul li {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: .5rem;
    border-bottom: 1px solid #eee;
}
.vm_goods_attr_info li {
    margin: .5rem;
}
.l_b_nav {
    margin: 0 10%;
    position: fixed;
    bottom: 0;
    height: 2.5rem;
    line-height: 2.5rem;
    font-size: 1.2rem;
    width: 80%;
    text-align: center;
    display: flex;
    justify-content: center;
    background: #FFF;
    padding: .5rem 0;
}
.l_b_nav div {
    width: 50%;
    background: #87CEFA;
    color: #FFF;
}
</style>

    <div class="inde_slider">

        <ul>
            @foreach (explode('|', $goods->goods_gallery) as $element)
                <li>
                    <a href="#"><img src="{{ url($element) }}" alt="{{ $goods->goods_name }}"></a>
                </li>
            @endforeach
        </ul>

        <script type="text/javascript" src="{{ asset('js/yxMobileSlider.js') }}"></script>
        <script type="text/javascript">
            $(".inde_slider").yxMobileSlider({width:640,height:640,during:3000})
        </script>
    </div>
    <div class="goods_cont">
        <h3>{{ $goods->goods_name }}</h3>
        <span>{{ $goods->goods_title }}</span>
        <p>{{ $goods->goods_point }}Cp <span>¥{{ $goods->goods_price }}</span></p>
        <div>销量:10000+</div>
    </div>

    <div class="blank"></div>
    <div class="vm_goods_attr" onclick="getAttr({{ $goods->id }})">
        <p>已经选择 <b>默认</b></p>
        <i class="fa fa-angle-right"></i>
    </div>

    <div class="blank"></div>
    <div class="vm_goods_desc">
        {!! $goods->goods_desc !!}
    </div>

    <div class="l_b_nav" style="margin: 0;width: 100%;">
        <div style="width: auto;line-height: 2.5rem;padding: 0 1rem;background: #FFF;color: #fe0;" >
            <img style="width: 1.2rem;height: 1.2rem;vertical-align: middle;" src="{{ asset('img/server.png') }}">
        </div>
        <div style="width: auto;line-height: 2.5rem;padding: 0 1rem;background: #FFF;color: #fe0;" >@if ($collection)
            <i class="fa fa-star"></i>
            @else
            <i class="fa fa-star-o"></i>
        @endif
        </div>
        <div style="border-top-left-radius: 2rem;border-bottom-left-radius: 2rem;" onclick="getAttr({{ $goods->id }})">加入购物车</div>
        <div style="background:#6495ed;border-top-right-radius: 2rem;border-bottom-right-radius: 2rem;" onclick="getAttr({{ $goods->id }})">立即购买</div>
    </div>



<script type="text/javascript">
    function getAttr(id) {
        load();
        $.post('{{ url('view/goods/get/attr') }}/'+id, {
            "_token" : '{{ csrf_token() }}'
        }, function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadBox)
            var attr = '';
            $.each(obj.data, function(index, val) {
                console.log(val);
                attr += '<p style="font-size: 1.2rem;margin-bottom: .5rem;">'+val.attr_name+'</p><ul class="vm_goods_attr_info" style="display:flex; justify-content: flex-start;flex-wrap: wrap;">'
                $.each(val.attr, function(i, v) {
                    if (i == 0) {
                        attr += '<li><input onchange="attrPoint()" id="'+v.id+'" name="attr'+val.id+'" data-point="'+v.attr_point+'" type="radio" checked/><label for="'+v.id+'">'+v.attr_name+'</label></li>';
                    }else{
                        attr += '<li><input onchange="attrPoint()" id="'+v.id+'" name="attr'+val.id+'" data-point="'+v.attr_point+'" type="radio"/><label for="'+v.id+'">'+v.attr_name+'</label></li>';
                    }
                });
                attr += '</ul>';
            });
            // console.log(attr);
            if (obj.status == 'success') {
                var html = '<div style="padding: 1rem;position: relative;display: flex;justify-content: flex-start;">\
                <img style="width:8rem;height:8rem;" src="{{ url($goods->goods_pic) }}" />\
                <div style="margin-left: 1rem;">\
                    <h4 style="margin-top: 1rem;font-size: 1.5rem; color:#6495ed;"><a style="font-size: 1.5rem; color:#6495ed;" id="c_point">{{ $goods->goods_point }}</a>Cp <span style="font-size:1.2rem;color:#666;">¥<a id="c_price" style="font-size:1.2rem;color:#666;">{{ $goods->goods_price }}</a></span></h4>\
                    <ol style="font-size: 1.2rem;margin: 1rem 0;">库存:98件</ol>\
                    <p style="font-size: 1rem;">已选择 <b id="c_attr_name"></b></p>\
                </div>\
            </div>\
            <div style="padding:0 1rem;">\
                <div style="border-top:1px solid #CCC;border-bottom:1px solid #CCC;padding: .5rem 0;    overflow-x: scroll;max-height: 14rem;">\
                '+attr+'\
                </div>\
                <div style="display: flex;justify-content: space-between;padding: .5rem;font-size: 1.2rem;height: 2rem;line-height: 2rem;"><p>购买数量:</p><p><i onclick="down()" class="fa fa-minus-square"></i><input style="height: 2rem;line-height: 2rem;width: 3rem;text-align: center;padding: 0;margin: 0;border: 0;font-size: 1.5rem;" type="text" name="num" disabled="disabled" value="1" /><i onclick="up()" class="fa fa-plus-square"></i></p></div></div><div class="l_b_nav"><div style="border-top-left-radius: 2rem;border-bottom-left-radius: 2rem;" onclick="addCart()">加入购物车</div><div style="background:#6495ed;border-top-right-radius: 2rem;border-bottom-right-radius: 2rem;" onclick="addCart()">立即购买</div></div>'
                layer.open({
                    type: 1
                    ,content: html
                    ,anim: 'up'
                    ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 500px; border:none;border-top-left-radius: 1rem;border-top-right-radius: 1rem;'
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

    // 点击属性重新计算价格进行赋值
    function attrPoint() {
        var point = parseInt({{ $goods->goods_point }})
        $.each($('input:checked'), function(index, val) {
            // console.log(val)
            // console.log($(this).attr('data-point'));
            point += parseInt($(this).attr('data-point'));
        });
        var num = $('input[name=num]').val();
        // console.log(point)
        $('#c_point').html(point * parseInt(num))
        $('#c_price').html(point * parseInt(num))
    }

    // 数量加减操作
    function up() {
        var num = $('input[name=num]').val();
        var totalPrice = $('#c_price').text();
        var totalPoint = $('#c_point').text();
        var newNum = parseInt(num) + 1;
        // 获取库存
        var newPrice = parseFloat(totalPrice) / parseInt(num) * newNum;
        var newPoint = parseFloat(totalPoint) / parseInt(num) * newNum;
        $('input[name=num]').val(newNum);
        $('#c_price').text(parseFloat(newPrice));
        $('#c_point').text(parseFloat(newPoint));

    }
    function down() {
        var num = $('input[name=num]').val();
        var totalPrice = $('#c_price').text();
        var totalPoint = $('#c_point').text();
        var newNum = parseInt(num) - 1;
        if (newNum < 1) {
            layer.open({
                content: '购买的数量不能为0'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            var newPrice = parseFloat(totalPrice) / parseInt(num) * newNum;
            var newPoint = parseFloat(totalPoint) / parseInt(num) * newNum;
            $('input[name=num]').val(newNum);
            $('#c_price').text(parseFloat(newPrice));
            $('#c_point').text(parseFloat(newPoint));
        }
    }

    function addCart() {
        // 获取选中的属性
        var array = [];
        $.each($('input:checked'), function(index, val) {
            array[index] = val.id;
        });
        var num = $('input[name=num]').val();
        load();
        $.post('{{ url('view/cart/addCart') }}/'+{{ $goods->id }}, {
            "_token" : '{{ csrf_token() }}',
            "attr" : array,
            "num" : num
        }, function (ret) {
            layer.close(loadBox)
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                setTimeout(function () {
                    location.href="{{ url('view/cart') }}"
                }, 1300);
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
