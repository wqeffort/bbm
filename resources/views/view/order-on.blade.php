@extends('lib.view.header')
@section('body')
<style type="text/css">

.order_ads {
    position: relative;
}
.order_ads ul {
    padding: .5rem .5rem 0 .5rem;
}
.order_ads ul li {
    line-height: 2rem;
}
.order_ads ul li cite {
    background: #6495ED;
    color: #FFF;
    padding: .2rem .5rem;
    border-radius: .3rem;
}
.order_ads p {
    font-size: 1.2rem;
}

.order_ads span {
    position: absolute;
    right: 1rem;
    top: 4rem;
    font-size: 1.5rem;
}

.order_pay {
    position: relative;
    font-size: 1rem;
}
.order_pay ul {
    padding: .5rem;
}
.order_pay ul li {
    line-height: 2rem;
    display: flex;
    border-bottom: 1px solid #EEE;
    justify-content: space-between;
}
.order_pay ul li i {
    line-height: 2rem;
    font-size: 1.2rem;
    padding: 0 1rem;
}
input[type='radio'] {
    display: none;
}
label {
    color: #999;
}
input[type="radio"]:checked + label {
    color: #6495ed;
    border: none;
    padding: 0;
}

.order_mark {
    position: relative;
    width: 100%;
    font-size: 1rem;
}
.order_mark ul {
    padding: .5rem;
}
.order_mark ul textarea {
    margin: .5rem 0;
    width: 97%;
    font-size: 1rem;
    color: #666;
}
ol {
    background: #6395ed;
    color: #FFF;
    padding: .5rem;
    font-size: 1rem;
}
.order_goods {
    position: relative;
}
.order_goods ul {
    padding: .5rem;
}


.payTabs a {
        color: #333;padding: 1rem;
    }
    .payTabs a.active{color: #6495ed;border-bottom: 2px solid #6495ed;padding: 1rem;}
    .swiper-container{border-radius:0 0 5px 5px;width:100%;border-top:0;}
    .swiper-slide{width:100%;background:none;color:#000;}
    /*.content-slide{padding:25px;}*/
    /*.content-slide p{text-indent:2em;line-height:1.9;}*/
    .swiper-container {margin:0 auto;position:relative;overflow:hidden;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;/* Fix of Webkit flickering */    z-index:1;    height: auto;
    overflow: hidden;}
    .swiper-wrapper {position:relative;width:100%;
        -webkit-transition-property:-webkit-transform, left, top;
        -webkit-transition-duration:0s;
        -webkit-transform:translate3d(0px,0,0);
        -webkit-transition-timing-function:ease;
        -moz-transition-property:-moz-transform, left, top;
        -moz-transition-duration:0s;
        -moz-transform:translate3d(0px,0,0);
        -moz-transition-timing-function:ease;
        -o-transition-property:-o-transform, left, top;
        -o-transition-duration:0s;
        -o-transform:translate3d(0px,0,0);
        -o-transition-timing-function:ease;
        -o-transform:translate(0px,0px);
        -ms-transition-property:-ms-transform, left, top;
        -ms-transition-duration:0s;
        -ms-transform:translate3d(0px,0,0);
        -ms-transition-timing-function:ease;
        transition-property:transform, left, top;
        transition-duration:0s;
        transform:translate3d(0px,0,0);
        transition-timing-function:ease;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
    }
    .swiper-free-mode > .swiper-wrapper {
        -webkit-transition-timing-function: ease-out;
        -moz-transition-timing-function: ease-out;
        -ms-transition-timing-function: ease-out;
        -o-transition-timing-function: ease-out;
        transition-timing-function: ease-out;
        margin: 0 auto;
    }
    .swiper-slide {
        float: left;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
    }

    .content-slide ul li img {
        width: 8rem;
        height: 8rem;
        margin-right: 1rem;
        border-radius: .5rem;
    }
    .payTabs {
        text-align: center;
        background: #FFF;
        font-size: 1rem;
        display: flex;
        justify-content: center;
    }
    .swiper-container ul li p{
        line-height: 1.5rem;
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
<div class="order_ads">
    <ol>订单编号: {{ $num}}</ol>
    @if ($ads)
        <ul onclick="ads()" id="ads">
            <input type="hidden" name="adsId" value="{{ $ads->id }}">
            <input type="hidden" name="isAds" value="true">
            <li>
                <p><b>{{ $ads->name }}</b> {{ $ads->phone }}</p>
            </li>
            <li>
                <p style="font-size: 1rem;"><cite>默认</cite> {{ $ads->province }}{{ $ads->city }}{{ $ads->area }}{{ $ads->ads }} </p>
            </li>
        </ul>
        <span><i class="fa fa-angle-right"></i></span>
    @else
        <ul>
            <input type="hidden" name="isAds" value="false">
            <p style="font-size:1rem;text-align: center;margin: 1rem 0 0 0;">未设置收货地址:<a href="{{ url('view/ads') }}">请点击添加地址</a></p>
        </ul>
    @endif
    <p><img style="height: .1rem;width: 100%;" src="{{ asset('img/order_nav.png') }}"></p>
</div>
<div class="blank"></div>
<div class="order_pay">
    @if ($pay_type == 2)
        <ul>
            <li>请选择支付方式</li>
            <li>
                <p>
                    <i class="fa fa-rub"></i> 积分支付
                </p>
                <input type="radio" name="pay" value="point" id="point" checked="checked">
                <label for="point"><i class="fa fa-check-circle"></i></label>
            </li>
            <li>
                <p>
                    <i class="fa fa-weixin"></i> 微信支付
                </p>
                <input type="radio" name="pay" value="weixin" id="weixin">
                <label for="weixin"><i class="fa fa-check-circle"></i></label>
                </li>
            <li>
                <p>
                    <i class="fa fa-circle-o-notch fa-spin"></i> 支付宝支付(稍后开通)
                </p>
                <input type="radio" name="pay" value="zfb" id="zfb" disabled>
                <label for="zfb"><i class="fa fa-check-circle"></i></label>
            </li>
        </ul>
    @else
        <ul>
            <li>请选择支付方式</li>
            <li>
                <p>
                    <i class="fa fa-rub"></i> 积分支付
                </p>
                <input type="radio" name="pay" value="point" id="point">
                <label for="point"><i class="fa fa-check-circle"></i></label>
            </li>
            <li>
                <p>
                    <i class="fa fa-weixin"></i> 微信支付
                </p>
                <input type="radio" name="pay" value="weixin" id="weixin" checked="checked">
                <label for="weixin"><i class="fa fa-check-circle"></i></label>
                </li>
            <li>
                <p>
                    <i class="fa fa-circle-o-notch fa-spin"></i> 支付宝支付(稍后开通)
                </p>
                <input type="radio" name="pay" value="zfb" id="zfb" disabled>
                <label for="zfb"><i class="fa fa-check-circle"></i></label>
            </li>
        </ul>
    @endif
</div>

<div class="blank"></div>

<div class="order_mark">
    <ul>
        <li>订单备注:</li>
        <li>
            <textarea id="mark">建议留言前先与客服沟通确认</textarea>
        </li>
    </ul>
</div>

<div class="blank"></div>

<div class="order_goods">
    <ul>
        <li style="font-size: 1rem;margin-bottom: .5rem;">订单详情:</li>
        @foreach ($order as $value)
            <li style="display: flex;justify-content: flex-start; border-bottom: 2px solid #EEE;padding: .5rem 0;position: relative;">
                <img style="width: 6rem;" src="http://jaclub.shareshenghuo.com/{{ $value->goods_img }}">
                <ul>
                    <li><h3>{{ $value->goods_name }}</h3></li>
                    <li>@foreach ($value->attr as $element)
                        <span>{{ $element }}</span>
                    @endforeach</li>
                    <li style="display: flex;justify-content: space-between;font-size: 1rem;margin-top: 1rem;"><p style="color:#6495ED">{{ $value->point }}Cp <span style="color:#999;">¥{{ $value->price }}</span></p><span style="position: absolute;bottom: 1rem;right: 1rem">数量:{{ $value->goods_num }}</span></li>
                </ul>
            </li>
        @endforeach
    </ul>
</div>

<div style="height: 10rem;background: #EEE"></div>

<div class="order_b" style="    border-top: 1px solid #EEE;font-size: 1rem;position: fixed;bottom: 0;background: #FFF;width: 100%;">
    <ul style="display: flex;justify-content: space-between;padding: 1rem;">
        <li>共计 {{ $total_num }} 件 合计: <b style="color:#6495ED">{{ $total }}Cp</b> <span style="color: #999;">¥{{ $total }}</span></li>
        <li><a style="padding: .4rem 1.5rem;background: #6495ED;color: #FFF;border-radius: 1rem;" href="javascript:;" >售 后</a></li>
    </ul>
</div>
<script src="{{ asset('js/idangerous.swiper.min.js') }}"></script>
<script type="text/javascript">
// // 进入页面后默认检查一次地址
// $(function () {
//     load();
//     $.post('{{ url('view/ads/select') }}', {
//         "_token" : '{{ csrf_token() }}'
//     }, function (ret) {
//         var obj = $.parseJSON(ret);
//         layer.close(loadBox)
//         if (obj.status == 'success') {
//             var html = '<input type="hidden" name="adsId" value="'+obj.data.id+'">\
//                     <input type="hidden" name="isAds" value="true">\
//             <li>\
//                 <p><b>'+obj.data.name+'</b> '+obj.data.phone+'</p>\
//             </li>\
//             <li>\
//                 <p style="font-size: 1rem;"><cite>默认</cite> '+obj.data.province+obj.data.city+obj.data.area+obj.data.ads+'</p>\
//             </li>';
//             // console.log(html)
//             $('#ads').html(html);
//         }
//     })
// })

// // 获取地址列表
// function ads() {
//     location.href="{{ url('view/ads') }}"
// }

// // 后台录入地址和备注
// function editOrder(num) {
//     // 获取到订单地址
//     var ads = $('input[name=adsId]').val();
//     // 获取到订单备注
//     var mark = $("#mark").val();
//     load();
//     $.post('{{ url('view/order/edit/ads') }}', {
//         "_token" : '{{ csrf_token() }}',
//         "ads" : ads,
//         "mark" : mark,
//         "num" : num
//     }, function (ret) {
//         layer.close(loadBox)
//         var obj = $.parseJSON(ret);
//         if (obj.status == 'success') {
//             pay(num);
//         }else{
//             layer.open({
//                 content: obj.msg
//                 ,btn: '我知道了'
//             });
//         }
//     })
// }

// function pay(num) {
//     // console.log(num);
//     // 获取结算方式
//     var payType = $("input[name='pay']:checked").val();
//     // console.log(payType);
//     // load();

//     if (payType == 'weixin') {
//         $.post('{{ url('view/payment/wechat') }}', {
//             "_token" : '{{ csrf_token() }}',
//             "num" : num,
//             "type" : 'shop'
//         }, function (ret) {
//             // 唤醒app进行支付
//             var obj = $.parseJSON(ret);
//             if (obj.status == 'success') {
//                 console.log(obj.data);
//                 console.log(getOS());
//                 if (getOS() == 'ios') {
//                     openIos(JSON.stringify(obj.data))
//                     // 执行轮询方法
//                 }else if (getOS() == 'android') {
//                     openAndroid(JSON.stringify(obj.data))
//                     // 执行轮询方法
//                 }
//             }else{
//                 layer.open({
//                     content: obj.msg
//                     ,btn: '我知道了'
//                 });
//             }
//         })
//     }else if (payType == 'point') {
//         // load();
//         // 弹出层 选择支付密码或者短信验证码
//         var html = '<li  style="display: flex;justify-content: flex-start;background: #6395ed;color: #FFF;">\
//             <img style="width: 5rem;height:5rem;border-radius: 50%;padding: .5rem;margin-right: .5rem;" src="{{ url($user->user_pic) }}" alt="用户头像" />\
//             <div>\
//                 <p style="line-height:1.5rem;">用户名称:@if ($user->user_name) {{ $user->user_name}}@else{{ $user->user_nickname}}@endif</p>\
//                 <p style="line-height:1.5rem;">基本积分: {{ $user->user_point }}</p>\
//                 <p style="line-height:1.5rem;">赠送积分: {{ $user->user_point_give }}</p>\
//                 <p style="line-height:1.5rem;">开拓积分: {{ $user->user_point_open }}</p>\
//             </div>\
//         </li>';
//         layer.open({
//             type: 1
//             ,content: '<div>\
//                 <div class="payTabs">\
//                     <a href="#" hidefocus="true" class="active">密码支付</a>\
//                     <a href="#" hidefocus="true">验证码支付</a>\
//                 </div>\
//                 <div class="swiper-container">\
//                     <div class="swiper-wrapper">\
//                         <div class="swiper-slide">\
//                             <div class="content-slide">\
//                                 <div class="order_bg" style="padding: .5rem;position: relative;">\
//                                     <ul>\
//                                         '+html+'\
//                                         <li style="text-align: center;margin-top: .5rem;padding-top: .5rem;border-top: 1px solid #EEE;font-size: 1rem;">\
//                                             <p style="font-size: 1.2rem;background: #EEE;padding: .5rem;margin-bottom: .5rem;">积分总价: <span style="color:#C40000;">{{ $total }}</span></p>\
//                                             <p style="margin: 2rem 0;">请输入支付密码: <input type="password" name="password"/></p>\
//                                             <p style="margin-top: 1rem;background: #6395ed;color: #FFF;padding: .5rem 0;" onclick="pointPay(1)">支 付</p>\
//                                         </li>\
//                                     </ul>\
//                                 </div>\
//                             </div>\
//                         </div>\
//                         <div class="swiper-slide">\
//                             <div class="content-slide">\
//                                 <div class="order_bg" style="padding: .5rem;position: relative;">\
//                                     <ul>\
//                                         '+html+'\
//                                         <li style="text-align: center;margin-top: .5rem;padding-top: .5rem;border-top: 1px solid #EEE;font-size: 1rem;">\
//                                             <p style="font-size: 1.2rem;background: #EEE;padding: .5rem;margin-bottom: .5rem;">积分总价: <span style="color:#C40000;">{{ $total }}</span></p>\
//                                             <p class="change" style="margin: 2rem 0;">发送验证码至 <span style="color:#C40000;">{{ $user->user_phone }}</span> 手机</p>\
//                                             <p class="change" style="margin-top: 1rem;background: #6395ed;color: #FFF;padding: .5rem 0;" onclick=sendSms("{{ $user->user_phone }}")>点击发送</p>\
//                                             <p class="default" style="display:none;margin: 2rem 0;">请输入验证码: <input type="number" name="code"/></p>\
//                                             <p class="default" style="display:none;margin-top: 1rem;background: #6395ed;color: #FFF;padding: .5rem 0;" onclick="pointPay(2)">支 付</p>\
//                                         </li>\
//                                     </ul>\
//                                 </div>\
//                             </div>\
//                         </div>\
//                     </div>\
//                 </div>\
//             </div>'
//             ,anim: 'up'
//             ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 350px; padding:10px 0; border:none;'
//         });
//         // 分类滑动
//         var tabsSwiper = new Swiper('.swiper-container',{
//             speed:500,
//             onSlideChangeStart: function(){
//                 $(".payTabs .active").removeClass('active');
//                 $(".payTabs a").eq(tabsSwiper.activeIndex).addClass('active');
//             }
//         });

//         $(".payTabs a").on('touchstart mousedown',function(e){
//             e.preventDefault()
//             $(".payTabs .active").removeClass('active');
//             $(this).addClass('active');
//             tabsSwiper.swipeTo($(this).index());
//         });

//         $(".payTabs a").click(function(e){
//             e.preventDefault();
//         });

//         $(function () {
//             $('.swiper-slide-visible').css('height', 'auto');
//         })
//     }
// }

// function openAndroid(json) {
//     android.weChatPaysMent(json);
// }

// function openIos(json) {
//     window.webkit.messageHandlers.iosWechatPayment.postMessage(json)
// }

// function orderNotice(type) {
//     layer.close(loadBox)
//     if (type == '0') {
//         layer.open({
//             content: '支付成功!'
//             ,btn: '我知道了'
//         });
//         location.href="{{ url('/view/order') }}"
//     }else{
//         layer.open({
//             content: '支付失败!'
//             ,btn: '我知道了'
//         });
//     }
// }

// function sendSms(phone) {
//     load();
//     if (isPhone(phone)) {
//         $.post('{{ url('sendsmsPost') }}', {
//             "_token" : '{{ csrf_token() }}',
//             "phone" : phone
//         }, function(ret) {
//             layer.close(loadBox);
//             var obj = $.parseJSON(ret);
//             if (obj.status == 'success') {
//                 layer.open({
//                     content: obj.msg
//                     ,skin: 'msg'
//                     ,time: 2 //2秒后自动关闭
//                 });
//                 // 改变输入框状态
//                 $('.change').css('display', 'none');
//                 $('.default').css('display', 'block');
//             }else{
//                 layer.open({
//                     content: obj.msg
//                     ,skin: 'msg'
//                     ,time: 2 //2秒后自动关闭
//                 });
//             }
//         });
//     }else{
//         layer.open({
//             content: '您的手机号码格式不正确,请到个人中心进行重新绑定,或者联系客服进行处理!'
//             ,btn: '我知道了'
//         });
//     }
// }

// function pointPay(type) {
//     load();
//     var password = $('input[name=password]').val();
//     var code = $('input[name=code]').val();
//     var v = false;
//     if (type == 1) {
//         if (password.length < 4) {
//             layer.open({
//                 content: '支付密码格式不正确!'
//                 ,btn: '我知道了'
//             });
//         }else{
//             v = true;
//         }
//     }else{
//         if (password.length != 4) {
//             layer.open({
//                 content: '短信验证码格式不正确!'
//                 ,btn: '我知道了'
//             });
//         }else{
//             v = true;
//         }
//     }
//     if (v) {
//         $.post('{{ url('view/payment/point') }}', {
//             "_token" : '{{ csrf_token() }}',
//             "type" : type,
//             "num" : '{{ $num }}',
//             "password" : password,
//             "code" : code
//         }, function (ret) {
//             var obj = $.parseJSON(ret);
//             if (obj.status == 'success') {
//                 layer.closeAll()
//                 layer.open({
//                     content: obj.msg
//                     ,btn: '我知道了'
//                 });
//                 setTimeout(function () {
//                     // 跳转到订单路由
//                     location.href="{{ url('view/order') }}"
//                 }, 2500)
//             }else{
//                 layer.close(loadBox);
//                 layer.open({
//                     content: obj.msg
//                     ,btn: '我知道了'
//                 });
//             }
//         })
//     }
// }
</script>
@endsection
