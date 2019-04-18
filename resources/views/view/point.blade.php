@extends('lib.view.header')
@section('body')
<style type="text/css">
    body {
        background: #EEE;
    }
    .cateTabs a {
        color: #333;padding: 1rem;
    }
    .cateTabs a.active{color: #6495ed;border-bottom: 2px solid #6495ed;padding: 1rem;}
    .swiper-container{border-radius:0 0 5px 5px;width:100%;border-top:0;}
    .swiper-slide{width:100%;background:none;color:#fff;}
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
    /*.content-slide ul li {
        display: flex;
        justify-content: space-between;
        text-align: center;
        margin-bottom: 20px;
    }
    .content-slide ul li img {
        width: 70px;
        height: 70px;
    }*/
    .cateTabs {
        text-align: center;
        background: #FFF;
        font-size: 1rem;
        display: flex;
        justify-content: space-between;
    }
    .point_log {
        width: 100%;
        position: relative;
    }
    .point_log ul li {
        padding: .5rem;
    }
    .point_bg img {
        /*border: 1px dashed #666;*/
    }
    .log_bg {
        background: #FFF;
        color: #000;
        padding: .5rem 1rem;
        border-radius: .5rem;
    }
    .log_bg ol {
        display: flex;
        justify-content: space-between;
        line-height: 2rem;
        font-size: 1rem;
    }
    .log_bg .point_num {
        color: #CDAF7D;
    }
    ol span {
        color: #666;
    }
</style>

    <div class="vm_cate">
        <div class="cateTabs">
            <a href="#" hidefocus="true" class="active">基本积分</a>
            <a href="#" hidefocus="true">赠送积分</a>
            <a href="#" hidefocus="true">开拓积分</a>
        </div>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="content-slide">
                        <div class="point_bg" style="padding: 1rem;position: relative;">
                            <div style="position: absolute;top:35%;left:45%;">
                                <h3>基本积分</h3>
                                <span>{{ $user->user_point }}</span>
                            </div>
                            <img src="{{ asset('img/point_bg.png') }}">
                        </div>
                        <div class="point_log">
                            <ul class="point_info point">
                                {{-- <li>
                                    <div class="log_bg">
                                        <ol><h3>充值到账积分</h3><span class="point_num">1000</span></ol>
                                        <ol><span>2019-09-11 12:12:00</span><b>结余:1009</b></ol>
                                    </div>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="content-slide">
                        <div class="point_bg" style="padding: 1rem;position: relative;">
                            <div style="position: absolute;top:35%;left:45%;">
                                <h3>赠送积分</h3>
                                <span>{{ $user->user_point_give }}</span>
                            </div>
                            <img src="{{ asset('img/point_bg.png') }}">
                        </div>
                        <div class="point_log">
                            <ul class="point_info point_give">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="content-slide">
                        <div class="point_bg" style="padding: 1rem;position: relative;">
                            <div style="position: absolute;top:35%;left:45%;">
                                <h3>开拓积分</h3>
                                <span>{{ $user->user_point_open }}</span>
                            </div>
                            <img src="{{ asset('img/point_bg.png') }}">
                        </div>
                        <div class="point_log">
                            <ul class="point_info point_open">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/idangerous.swiper.min.js') }}"></script>
    <script type="text/javascript">
        // 分类滑动
        var tabsSwiper = new Swiper('.swiper-container',{
            speed:500,
            onSlideChangeStart: function(){
                $(".cateTabs .active").removeClass('active');
                $(".cateTabs a").eq(tabsSwiper.activeIndex).addClass('active');
            }
        });

        $(".cateTabs a").on('touchstart mousedown',function(e){
            e.preventDefault()
            $(".cateTabs .active").removeClass('active');
            $(this).addClass('active');
            tabsSwiper.swipeTo($(this).index());
        });

        $(".cateTabs a").click(function(e){
            e.preventDefault();
        });

    $(function () {
        $('.point_info').html('<p style="text-align: center;color: #333;font-size: 1rem;">加载数据中!</p>')
        // 获取页面数据
        $.post('{{ url('view/point/get/log') }}', {
            "_token" : '{{ csrf_token() }}'
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                console.log(obj.data)
                if (obj.data === undefined || obj.data == 0) {
                    $('.point_info').html('<p style="text-align: center;color: #333;font-size: 1rem;">未获取到数据</p>')
                }else{
                    var point = '';
                    var point_give = '';
                    var point_open = '';
                    $.each(obj.data, function(index, val) {
                        var type = '';
                        switch(val.type) {
                            case 1:
                                type = '商城购物'
                            break;
                            case 2:
                                type = '春蚕添加个人积分'
                            break;
                            case 3:
                                type = '加盟商为用户开通会籍送的积分'
                            break;
                            case 4:
                                type = '加盟商为用户充值的积分'
                            break;
                            case 5:
                                type = '活动赠送10积分'
                            break;
                            case 6:
                                type = '扣除年费积分'
                            break;
                            case 7:
                                type = '生日充值'
                            break;
                            case 8:
                                type = '后台开通会籍'
                            break;
                            case 9:
                                type = ''
                            break;
                            case 10:
                                type = '后台充值'
                            break;
                            case 11:
                                type = '商城订单取消返款'
                            break;
                            case 12:
                                type = '代客下单'
                            break;
                            case 13:
                                type = '后台操作扣除'
                            break;
                            case 14:
                                type = '系统返还'
                            break;
                            case 15:
                                type = '微信充值'
                            break;
                            case 16:
                                type = '微信购买会籍'
                            break;
                            case 17:
                                type = '充值开拓积分'
                            break;
                            case 18:
                                type = '开拓积分到期'
                            break;
                            default:
                            type = '未知原因'
                        }
                        if (parseInt(val.point - val.new_point) != 0) {
                            point += '<li>\
                                <div class="log_bg">\
                                    <ol><h3>'+type+'</h3><span class="point_num">'+parseInt(val.point - val.new_point)+'</span></ol>\
                                    <ol><span>'+val.created_at+'</span><b>结余:'+parseInt(val.new_point)+'</b></ol>\
                                </div>\
                            </li>'
                        }
                        if (parseInt(val.point_give - val.new_point_give) != 0) {
                            point_give += '<li>\
                                <div class="log_bg">\
                                    <ol><h3>'+type+'</h3><span class="point_num">'+parseInt(val.point_give - val.new_point_give)+'</span></ol>\
                                    <ol><span>'+val.created_at+'</span><b>结余:'+parseInt(val.new_point_give)+'</b></ol>\
                                </div>\
                            </li>'
                        }
                        if (parseInt(val.point_open - val.new_point_open) != 0) {
                            point_open += '<li>\
                                <div class="log_bg">\
                                    <ol><h3>'+type+'</h3><span class="point_num">'+parseInt(val.point_open - val.new_point_open)+'</span></ol>\
                                    <ol><span>'+val.created_at+'</span><b>结余:'+parseInt(val.new_point_open)+'</b></ol>\
                                </div>\
                            </li>'
                        }
                    });
                    $('.point_info').html('');
                    $('.point').html(point);
                    $('.point_give').html(point_give);
                    $('.point_open').html(point_open);
                    $('.swiper-slide-visible').css('height', 'auto');
                }
            }
        })
    })
    </script>
@endsection
