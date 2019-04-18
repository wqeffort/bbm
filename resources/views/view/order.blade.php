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

    .content-slide ul li img {
        width: 8rem;
        height: 8rem;
        margin-right: 1rem;
        border-radius: .5rem;
    }
    .cateTabs {
        text-align: center;
        background: #FFF;
        font-size: 1rem;
        display: flex;
        justify-content: space-between;
    }

    .order_bg ul {
        color: #000;
        font-size: 1rem;
    }
    .order_bg ul li {
        background: #FFF;
        padding: .5rem;
        border-radius: .5rem;
        margin-bottom: .5rem;
    }
    dt {
        display: flex;
        justify-content: space-between;
        height: 2rem;
        line-height: 1.5rem;
        font-size: .6rem;
    }
    ol {
        text-align: right;
        height: 2rem;
        line-height: 2.5rem;
    }
    .tab_info {
        display: flex;
        justify-content: flex-start;
        padding: .5rem 0;
        border-top: 1px solid #EEE;
        border-bottom: 1px solid #EEE;
    }
    h4 {
        font-size: 1rem;
        font-weight: 300;
    }
    .goods {
        padding: .5rem 0;
    }
    .goods h4 {
        margin: 1rem 0;
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

    <div class="vm_cate">
        <div class="cateTabs">
            <a href="#" hidefocus="true" class="active">待付款</a>
            <a href="#" hidefocus="true">待发货</a>
            <a href="#" hidefocus="true">待收货</a>
        </div>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="content-slide">
                        <div class="order_bg" style="padding: .5rem;position: relative;">
                            <ul>
                                @foreach ($order as $element)
                                    @if ($element['0']->status == 0 && $element['0']->del == 0)
                                        <li>
                                            <dl>
                                                <dt>
                                                    <span>{{ $element['0']->num }}</span>
                                                    <span>{{ $element['0']->created_at }}</span>
                                                </dt>
                                                <dd>
                                                    <div class="tab_info">
                                                        <img src="{{ url($element['0']->goods_pic) }}">
                                                        <div class="goods">
                                                            <p>
                                                            @if (count($element) > 2)
                                                                {{ $element['0']->goods_name }} <b>等{{ count($element) - 1 }}件商品</b>
                                                            @else
                                                                {{ $element['0']->goods_name }}
                                                            @endif
                                                            </p>
                                                            <h4>数量:1</h4>
                                                            <p>总价:{{ $element['total'] }} 积分 <span>¥{{ $element['total'] }}</span></p>
                                                        </div>
                                                    </div>
                                                </dd>
                                                <ol>
                                                    <a href="javascript:;">查看详情</a>
                                                    <a href="{{ url('view/order/set') }}/{{ $element['0']->num }}">现在支付</a>
                                                </ol>
                                            </dl>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="content-slide">
                        <div class="order_bg" style="padding: 1rem;position: relative;">
                            <ul>
                                @foreach ($order as $element)
                                    @if ($element['0']->status == 1 && $element['0']->express_status == 0)
                                        <li>
                                            <dl>
                                                <dt>
                                                    <span>{{ $element['0']->num }}</span>
                                                    <span>{{ $element['0']->created_at }}</span>
                                                </dt>
                                                <dd>
                                                    <div class="tab_info">
                                                        <img src="{{ url($element['0']->goods_pic) }}">
                                                        <div class="goods">
                                                            <p>
                                                            @if (count($element) > 2)
                                                                {{ $element['0']->goods_name }} <b>等{{ count($element) - 1 }}件商品</b>
                                                            @else
                                                                {{ $element['0']->goods_name }}
                                                            @endif
                                                            </p>
                                                            <h4>数量:1</h4>
                                                            <p>总价:{{ $element['total'] }} 积分 <span>¥{{ $element['total'] }}</span></p>
                                                        </div>
                                                    </div>
                                                </dd>
                                                <ol>
                                                    <a href="javascript:;">查看详情</a>
                                                    <a href="javascript:;">提醒发货</a>
                                                </ol>
                                            </dl>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="content-slide">
                        <div class="order_bg" style="padding: 1rem;position: relative;">
                            <ul>
                                @foreach ($order as $element)
                                    @if ($element['0']->status == 1 && $element['0']->express_status == 1)
                                        <li>
                                            <dl>
                                                <dt>
                                                    <span>{{ $element['0']->num }}</span>
                                                    <span>{{ $element['0']->created_at }}</span>
                                                </dt>
                                                <dd>
                                                    <div class="tab_info">
                                                        <img src="{{ url($element['0']->goods_pic) }}">
                                                        <div class="goods">
                                                            <p>
                                                            @if (count($element) > 2)
                                                                {{ $element['0']->goods_name }} <b>等{{ count($element) - 1 }}件商品</b>
                                                            @else
                                                                {{ $element['0']->goods_name }}
                                                            @endif
                                                            </p>
                                                            <h4>数量:1</h4>
                                                            <p>总价:{{ $element['total'] }} 积分 <span>¥{{ $element['total'] }}</span></p>
                                                        </div>
                                                    </div>
                                                </dd>
                                                <ol>
                                                    <a href="javascript:;">查看详情</a>
                                                    <a href="javascript:;">物流信息</a>
                                                </ol>
                                            </dl>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 10rem;"></div>
    
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
            $('.swiper-slide-visible').css('height', 'auto');
        })
    </script>
@endsection
