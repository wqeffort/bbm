@extends('lib.home.header')
@section('body')
<style type="text/css">
    .read {
        padding: .5rem 1rem;
        background: #EEE;
        color: #000;
    }
    .read h3 {
        color: #C40000;
    }
    .read ul li {
        line-height: 2rem;
    }
    .desk {
        padding: 1rem 5px;
    }
    .desk .t {
        text-align: center;
        padding: .5rem;
        background: #6fd1ff;
        color: #FFF;
    }
    .desk ul {
        display: flex;
        justify-content: space-between;
        padding: .5rem 0;
    }
    .desk ul li img {
        width: 5rem;
    }
    .desk ul li p {
        text-align: center;
        font-size: 1rem;
    }
    h6 {
        text-align: center;
        font-size: 1.2rem;
        padding-top: .5rem;
            background: #C40000;
        color: #FFF;
        margin: 1rem;
        margin-bottom: 0;
    }
    .payBox {
        /*border: 1px solid #C40000;
        margin: 1rem;*/
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

    .backBtn div {
        width: 2rem;
        height: 2rem;
        background: #FFF;
        z-index: 999;
        position: fixed;
        line-height: 2rem;
        text-align: center;
        border-radius: 50%;
        font-size: 1.5rem;
        left: 1rem;
        top: 1rem;
        color: #000;
        opacity: .7;
    }
    
</style>
<div>
    <img style="width: 100%;" src="{{ asset('images/ticket.jpg') }}" alt="梦享家三周年">
    {{-- 顶部漂浮按钮  --}}
    <a class="backBtn" href="javascript:;" onClick="javascript :history.back(-1);">
        <div><i class="fa fa-angle-left"></i></div>
    </a>
</div>
<div class="read">
    <h3>购票须知</h3>
    <ul>
        <li>
            <p>1. A区: 800.00 CP /位  B区: 600.00 CP /位</p>
        </li>
        <li>
            2. 每桌座席共计11席,购买10席位,送一席位(除去V1 ~ V5,10席)
        </li>
        <li>
            3. V1 ~ V9只能整桌座席一同购买
        </li>
        <li>
            4. <span style="padding: .5rem 1rem;background: #daecff;"></span>&nbsp;A区 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="padding: .5rem 1rem;background: #fffbda;"></span>&nbsp;B区
        </li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

<div class="desk">
    <div class="t">主舞台</div>
    <ul style="background: #daecff">
        <li onclick="buy({{ $t1->desk}}, {{$t1->price }})">
            <img src="{{ asset('images/ticket_v1.png') }}">
            <p>余 <b>{{ $t1->surplus - $t1->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t2->desk}}, {{$t2->price }})">
            <img src="{{ asset('images/ticket_v2.png') }}">
            <p>余 <b>{{ $t2->surplus - $t2->buy }}</b> 席</p>
        </li>
        <li>
            <img src="{{ asset('images/ticket_v0.png') }}">
            <p>余 <b>0</b> 席</p>
        </li>
        <li onclick="buy({{ $t3->desk}}, {{$t3->price }})">
            <img src="{{ asset('images/ticket_v3.png') }}">
            <p>余 <b>{{ $t3->surplus - $t3->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t5->desk}}, {{$t5->price }})">
            <img src="{{ asset('images/ticket_v5.png') }}">
            <p>余 <b>{{ $t5->surplus - $t5->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #daecff">
        <li onclick="buy({{ $t6->desk}}, {{$t6->price }})">
            <img src="{{ asset('images/ticket_v6.png') }}">
            <p>余 <b>{{ $t6->surplus - $t6->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t7->desk}}, {{$t7->price }})">
            <img src="{{ asset('images/ticket_v7.png') }}">
            <p>余 <b>{{ $t7->surplus - $t7->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t8->desk}}, {{$t8->price }})">
            <img src="{{ asset('images/ticket_v8.png') }}">
            <p>余 <b>{{ $t8->surplus - $t8->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t9->desk}}, {{$t9->price }})">
            <img src="{{ asset('images/ticket_v9.png') }}">
            <p>余 <b>{{ $t9->surplus - $t9->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #daecff">
        <li onclick="buy({{ $t10->desk}}, {{$t10->price }})" >
            <img src="{{ asset('images/ticket_v10.png') }}">
            <p>余 <b>{{ $t10->surplus - $t10->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t11->desk}}, {{$t11->price }})">
            <img src="{{ asset('images/ticket_v11.png') }}">
            <p>余 <b>{{ $t11->surplus - $t11->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t12->desk}}, {{$t12->price }})">
            <img src="{{ asset('images/ticket_v12.png') }}">
            <p>余 <b>{{ $t12->surplus - $t12->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t13->desk}}, {{$t13->price }})">
            <img src="{{ asset('images/ticket_v13.png') }}">
            <p>余 <b>{{ $t13->surplus - $t13->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #daecff">
        <li onclick="buy({{ $t15->desk}}, {{$t15->price }})">
            <img src="{{ asset('images/ticket_v15.png') }}">
            <p>余 <b>{{ $t15->surplus - $t15->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t16->desk}}, {{$t16->price }})">
            <img src="{{ asset('images/ticket_v16.png') }}">
            <p>余 <b>{{ $t16->surplus - $t16->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t17->desk}}, {{$t17->price }})">
            <img src="{{ asset('images/ticket_v17.png') }}">
            <p>余 <b>{{ $t17->surplus - $t17->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t18->desk}}, {{$t18->price }})">
            <img src="{{ asset('images/ticket_v18.png') }}">
            <p>余 <b>{{ $t18->surplus - $t18->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #fffbda">
        <li onclick="buy({{ $t19->desk}}, {{$t19->price }})">
            <img src="{{ asset('images/ticket_v19.png') }}">
            <p>余 <b>{{ $t19->surplus - $t19->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t20->desk}}, {{$t20->price }})">
            <img src="{{ asset('images/ticket_v20.png') }}">
            <p>余 <b>{{ $t20->surplus - $t20->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t21->desk}}, {{$t21->price }})">
            <img src="{{ asset('images/ticket_v21.png') }}">
            <p>余 <b>{{ $t21->surplus - $t21->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t22->desk}}, {{$t22->price }})">
            <img src="{{ asset('images/ticket_v22.png') }}">
            <p>余 <b>{{ $t22->surplus - $t22->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #fffbda">
        <li onclick="buy({{ $t23->desk}}, {{$t23->price }})">
            <img src="{{ asset('images/ticket_v23.png') }}">
            <p>余 <b>{{ $t23->surplus - $t23->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t25->desk}}, {{$t25->price }})">
            <img src="{{ asset('images/ticket_v25.png') }}">
            <p>余 <b>{{ $t25->surplus - $t25->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t26->desk}}, {{$t26->price }})">
            <img src="{{ asset('images/ticket_v26.png') }}">
            <p>余 <b>{{ $t26->surplus - $t26->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t27->desk}}, {{$t27->price }})">
            <img src="{{ asset('images/ticket_v27.png') }}">
            <p>余 <b>{{ $t27->surplus - $t27->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t28->desk}}, {{$t28->price }})">
            <img src="{{ asset('images/ticket_v28.png') }}">
            <p>余 <b>{{ $t28->surplus - $t28->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #fffbda">
        
        <li onclick="buy({{ $t29->desk}}, {{$t29->price }})">
            <img src="{{ asset('images/ticket_v29.png') }}">
            <p>余 <b>{{ $t29->surplus - $t29->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t30->desk}}, {{$t30->price }})">
            <img src="{{ asset('images/ticket_v30.png') }}">
            <p>余 <b>{{ $t30->surplus - $t30->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t31->desk}}, {{$t31->price }})">
            <img src="{{ asset('images/ticket_v31.png') }}">
            <p>余 <b>{{ $t31->surplus - $t31->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t32->desk}}, {{$t32->price }})">
            <img src="{{ asset('images/ticket_v32.png') }}">
            <p>余 <b>{{ $t32->surplus - $t32->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t33->desk}}, {{$t33->price }})">
            <img src="{{ asset('images/ticket_v33.png') }}">
            <p>余 <b>{{ $t33->surplus - $t33->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #fffbda">
        <li onclick="buy({{ $t35->desk}}, {{$t35->price }})">
            <img src="{{ asset('images/ticket_v35.png') }}">
            <p>余 <b>{{ $t35->surplus - $t35->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t36->desk}}, {{$t36->price }})">
            <img src="{{ asset('images/ticket_v36.png') }}">
            <p>余 <b>{{ $t36->surplus - $t36->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t37->desk}}, {{$t37->price }})">
            <img src="{{ asset('images/ticket_v37.png') }}">
            <p>余 <b>{{ $t37->surplus - $t37->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t38->desk}}, {{$t38->price }})">
            <img src="{{ asset('images/ticket_v38.png') }}">
            <p>余 <b>{{ $t38->surplus - $t38->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t39->desk}}, {{$t39->price }})">
            <img src="{{ asset('images/ticket_v39.png') }}">
            <p>余 <b>{{ $t39->surplus - $t39->buy }}</b> 席</p>
        </li>
    </ul>
    <ul style="background: #fffbda">
        
        <li onclick="buy({{ $t50->desk}}, {{$t50->price }})">
            <img src="{{ asset('images/ticket_v50.png') }}">
            <p>余 <b>{{ $t50->surplus - $t50->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t51->desk}}, {{$t51->price }})">
            <img src="{{ asset('images/ticket_v51.png') }}">
            <p>余 <b>{{ $t51->surplus - $t51->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t52->desk}}, {{$t52->price }})">
            <img src="{{ asset('images/ticket_v52.png') }}">
            <p>余 <b>{{ $t52->surplus - $t52->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t53->desk}}, {{$t53->price }})">
            <img src="{{ asset('images/ticket_v53.png') }}">
            <p>余 <b>{{ $t53->surplus - $t53->buy }}</b> 席</p>
        </li>
        <li onclick="buy({{ $t55->desk}}, {{$t55->price }})">
            <img src="{{ asset('images/ticket_v55.png') }}">
            <p>余 <b>{{ $t55->surplus - $t55->buy }}</b> 席</p>
        </li>
    </ul>
</div>
<script>
var page = '';
    // 获取屏幕宽度.计算桌子图片的宽度
    $(function () {
        var width = $(window).width();
        $('.desk > ul > li > img').css("width",(width - 10) / 5);
    });

    function buy(desk,price) {
        var html = '<div style="padding: 1rem;"><h3 style="    padding: 0 .5rem;line-height: 1rem;height: 2rem;">您当前选择的桌子为 v'+desk+'</h3><p id="priceB">单席价格:<b>'+price+'</b> CP</p><p>整桌价格:<b>'+parseInt(price) * 10+'</b> CP</p><div style="display:flex;"><input style="display:none;" name="type" type="radio" id="typeA" value="all" checked/><label style="padding: .2rem .5rem;color: #333;margin:1rem;font-weight: bold;" for="typeA" onclick=fn("all",'+price+')>整桌购买</label><input style="display:none;" name="type" type="radio" id="typeB" value="one"/><label style="padding: .2rem .5rem;color: #333;margin:1rem;font-weight: bold;" for="typeB" onclick=fn("one",'+price+')>席位购买</label></div><p id="num" style="display:none;">购买数量:<input style="display:none;" name="num" type="radio" id="num1" value="1" checked/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num1" onclick=setPrice("1",'+price+')>1</label><input style="display:none;" name="num" type="radio" id="num2" value="2"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num2" onclick=setPrice("2",'+price+')>2</label><input style="display:none;" name="num" type="radio" id="num3" value="3"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num3" onclick=setPrice("3",'+price+')>3</label><input style="display:none;" name="num" type="radio" id="num4" value="4"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num4" onclick=setPrice("4",'+price+')>4</label><input style="display:none;" name="num" type="radio" id="num5" value="5"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num5" onclick=setPrice("5",'+price+')>5</label><input style="display:none;" name="num" type="radio" id="num6" value="6"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num6" onclick=setPrice("6",'+price+')>6</label><input style="display:none;" name="num" type="radio" id="num7" value="78"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num7" onclick=setPrice("7",'+price+')>7</label><input style="display:none;" name="num" type="radio" id="num8" value="8"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num8" onclick=setPrice("8",'+price+')>8</label><input style="display:none;" name="num" type="radio" id="num9" value="9"/><label style="padding: .2rem .5rem;color: #333;font-weight: bold;" for="num9" onclick=setPrice("9",'+price+')>9</label></p><p style="margin: .5rem 0;">当前价格:<b id="price">'+parseInt(price) * 10+'</b> CP</p><p style="text-align: center;margin-top: 2rem;"><span style="padding: .5rem 1rem;color: #FFF;background: #C40000;" onclick="pay('+desk+')">现在去结算</span></p></div>'
        // <p><input style="display:none;" name="pay" type="radio" id="payA" value="price" checked/><label style="padding: .2rem .5rem;color: #333;margin:1rem;font-weight: bold;" for="payA"><i class="fa fa-wechat"></i> 微信支付</label><input style="display:none;" name="pay" type="radio" id="payB" value="point"/><label style="padding: .2rem .5rem;color: #333;margin:1rem;font-weight: bold;" for="payB"><i class="fa fa-database"></i> 积分抵扣</label></p>
        //页面层
        layer.open({
            type: 1
            ,content: html
            ,anim: 'up'
            ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 300px; border:none;'
        });
    }
    $("#numB").bind("input propertychange",function(){
        console.log(1);
        var price = parseInt($('#priceB').text());
        $('#price').text(parseInt($(this).val()) * price);
    });

    function fn(val,price) {
        if (val == 'one') {
            $('#num').css("display","block");
            $('#price').text(price)
        }else{
            $('#num').css("display","none");
            $('#price').text(parseInt(price) * 10)
        }
    }

    function setPrice(val,price) {
        var newPrice = parseInt(val) * parseInt(price);
        $('#price').text(newPrice)
    }

    function pay(desk) {
        // 获取到座位信息
        var type = $('input[name="type"]:checked').val();
        var num = $('input[name="num"]:checked').val();
        // var pay = $('input[name="pay"]:checked').val();
        var pay = 'point';
        if (type != '' && num != '' && pay != '') {
            // 存入专用的订单表
            load();
            $.post('{{ url('ticket/add') }}', {
                "_token" : '{{ csrf_token() }}',
                "type" : type,
                "num" : num,
                "pay" : pay,
                "desk" : desk
            }, function(ret) {
                var obj = $.parseJSON(ret);
                layer.closeAll();
                if (obj.status == 'success') {
                    // 弹出订单确认页面
                    if (obj.data.type == 1) {
                        var payType = '<li>结算金额: <b style="color:#C40000;">'+obj.data.price+'</b> 元</li>';
                        var passwordBox = '';
                    }else{
                        var payType = '<li>结算积分: <b style="color:#C40000">'+obj.data.price+'</b> 积分</li>';
                        var passwordBox = '请输入支付密码: <input type="password" name="password" />';
                    }
                    var html = '<span class="layer_close_btn" onclick="closePage()">X</span>\
<div class="payBox">\
    <dl>\
        <dd>\
            <ul>\
                <li>订单编号: <b>'+obj.data.order_num+'</b></li>\
                <li>购买座数: <b style="color:#C40000;"">'+obj.data.num+'</b> 座 (v1~v5为10座)</li>'+payType+'\
            </ul>\
        </dd>\
        <dd>\
            <ul>\
                <li>收 货 人: <input type="text" name="name" value="{{ session('user')->user_name }}"/></li>\
                <li>联系电话: <input type="number" name="phone" value="{{ session('user')->user_phone }}" pa/></li>\
            </ul>\
        </dd>\
        <hr>\
        <dd>\
            <ul>\
                <li>订单备注:</li>\
                <li><textarea style="width: 94%;height: 2rem;" id="mark" ></textarea></li>\
            </ul>\
        </dd>\
        <dd>\
            <ul>\
                <li>'+passwordBox+'</li>\
                <li><p style="text-align: center;margin-top: 2rem;"><span style="padding: .5rem 1rem;color: #FFF;background: #C40000;" onclick=goPay("'+obj.data.order_num+'",'+obj.data.type+')>现在支付</span></p></li>\
            </ul>\
        </dd>\
    </dl>\
</div>';
                    page = layer.open({
                        type: 1
                        ,content: html
                        ,anim: 'up'
                        ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
                    });
                }else{
                    layer.open({
                        content: obj.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            });
        }else{

        }
    }

    function closePage() {
        layer.close(page)
    }

    function goPay(num,type) {
        if (type == '1') {
            // 拉取微信支付
        }else{
            // 拉取积分抵扣
            var password = $('input[name=password]').val();
            var mark = $('#mark').val();
            var name = $('input[name=name]').val();
            var phone = $('input[name=phone]').val();
            if (password == '') {
                layer.open({
                    content: '请输入支付密码!'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }else if (name == '') {
                layer.open({
                    content: '收货人姓名不能为空!'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }else if (phone == '') {
                layer.open({
                    content: '收货人电话不能为空!'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }else{
                load();
                $.post('{{ url('ticket/pay') }}',{
                    "_token" : '{{ csrf_token() }}',
                    "password" : password,
                    "num" : num,
                    "mark" : mark,
                    "name" : name,
                    "phone" : phone
                },function (ret) {
                    var obj = $.parseJSON(ret);
                    if (obj.status == 'success') {
                        layer.closeAll();
                        layer.open({
                            content: obj.msg
                            ,btn: '我知道了'
                        });
                        setTimeout(function(){
                            location.href="{{ url('user/card') }}"
                        }, 1500);
                    }else{
                        layer.close(loadBox)
                        layer.open({
                            content: obj.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                });
            }
        }
    }
</script>
@endsection






































