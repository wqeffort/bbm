@extends('lib.view.header')
@section('body')
<style type="text/css">
    .vm_search_box {
        display: flex;
        justify-content: space-between;
        margin: .5rem;
    }
    .vm_search_box input {
        background: #EEE;
        border-radius: 1rem;
        width: 100%;
        height: 30px;
        padding: 0 1rem;
        text-align: center;
    }
    a {
        color: #333;
        font-size: 1.2rem;
    }
    .cateTabs a{
        padding-bottom: .3rem;
        font-size: 1.5rem;
        margin: 1rem;
    }
    .cateTabs a.active{color: #6495ed;border-bottom: 2px solid #6495ed;}
    .swiper-container{border-radius:0 0 5px 5px;width:100%;border-top:0;}
    .swiper-slide{width:100%;background:none;color:#fff;}
    .content-slide{padding:25px;}
    /*.content-slide p{text-indent:2em;line-height:1.9;}*/
    .swiper-container {margin:0 auto;position:relative;overflow:hidden;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;-ms-backface-visibility:hidden;-o-backface-visibility:hidden;backface-visibility:hidden;/* Fix of Webkit flickering */    z-index:1;    height: 250px;
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
    .content-slide ul li {
        display: flex;
        justify-content: space-between;
        text-align: center;
        margin-bottom: 20px;
    }
    .content-slide ul li img {
        width: 70px;
        height: 70px;
    }
    .cateTabs {
        text-align: center;
        margin: 1rem 0 0 0;
    }
    .vm-push-title {
        padding: .5rem;
    }
    .vm-push-title img {
        height: 30px;
        width: auto;
    }
    .vm-goods-box img {
        height: auto;
        width: 100%;
    }
    h3 {
        font-size: 1.2rem;
    }
    .vm-goods-box span {
        font-size: 1rem;
        color: #999;
    }
    .vm-goods-box {
        font-size: 1.2rem;
        margin-top: .5rem;
        width: 48%;
        padding: 0 1%;
        height: 18rem;
    }
    .vm-push-content {
        display: flex;
        flex-wrap: wrap;
    }
</style>
    {{-- 导航栏 --}}
    <div class="vm_nav" style="background:#6495ed;color: #FFF;text-align: center;padding: .5rem 0; font-size: .8rem; position: relative;">
        <h1>商 城</h1>
        {{-- <a style="position: absolute;left: 1rem;top: .3rem;font-size: 2rem;color: #FFF;" href="javascript:window.history.back();"><i class="fa fa-angle-left"></i></a> --}}
    </div>
    {{-- <div class="vm_search_box"> --}}
        <div class="search_box" style="background: #6395ed;position: relative;">
            <div class="search">
                <input style="background: #FFF" type="text" name="search" placeholder="请输入商品名称或者关键字进行搜索！" onchange="search()"><i class="fa fa-search" aria-hidden="true"></i>
                <span>搜索</span>
            </div>
        </div>
    {{-- </div> --}}
    {{-- 轮播开始 --}}
    {{-- <div class="home" style="display: block; margin-bottom: 10rem;"> --}}
    <!-- banner Start -->
    <div class="inde_slider">
        <ul>
            @foreach ($banner as $element)
                <li>
                    <a href="#"><img src="{{ url($element->img) }}" alt="{{ $element->title }}"></a>
                </li>
            @endforeach
        </ul>
        
        <script type="text/javascript" src="{{ asset('js/yxMobileSlider.js') }}"></script>
        <script type="text/javascript">
            $(".inde_slider").yxMobileSlider({width:640,height:300,during:3000})
        </script>
    </div>

    <div class="blank" style="height: .3rem"></div>
    
    {{-- 分类滑动开始 --}}
    <div class="vm_cate">
        <div class="cateTabs">
            <a href="#" hidefocus="true" class="active">品牌分类</a>
            <a href="#" hidefocus="true">普通分类</a>
        </div>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="content-slide">
                        <ul>
                            <li>
                                <a href="{{ url('view/goods/list/brand/1') }}">
                                    <img src="{{ asset('img/brand-1.png') }}">
                                    <p>凤天呈祥</p>
                                </a>
                                <a href="{{ url('view/goods/list/brand/4') }}">
                                    <img src="{{ asset('img/brand-2.png') }}">
                                    <p>梦味意求</p>
                                </a>
                                <a href="{{ url('view/goods/list/brand/2') }}">
                                    <img src="{{ asset('img/brand-3.png') }}">
                                    <p>金屋良缘</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('view/goods/list/brand/3') }}">
                                    <img src="{{ asset('img/brand-4.png') }}">
                                    <p>愿走高飞</p>
                                </a>
                                <a href="{{ url('view/goods/list/brand/5') }}">
                                    <img src="{{ asset('img/brand-5.png') }}">
                                    <p>融通四海</p>
                                </a>
                                <a href="{{ url('view/goods/list/brand/6') }}">
                                    <img src="{{ asset('img/brand-6.png') }}">
                                    <p>梦享家</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="content-slide">
                        <ul>
                            <li>
                                <a href="{{ url('view/goods/list/cate/3') }}">
                                    <img src="{{ asset('img/cate-1.png') }}">
                                    <p>医疗</p>
                                </a>
                                <a href="{{ url('view/goods/list/cate/6') }}">
                                    <img src="{{ asset('img/cate-2.png') }}">
                                    <p>食品</p>
                                </a>
                                <a href="{{ url('view/goods/list/cate/7') }}">
                                    <img src="{{ asset('img/cate-3.png') }}">
                                    <p>生活</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('view/goods/list/cate/') }}">
                                    <img src="{{ asset('img/cate-4.png') }}">
                                    <p>户外</p>
                                </a>
                                <a href="{{ url('view/goods/list/cate/5') }}">
                                    <img src="{{ asset('img/cate-5.png') }}">
                                    <p>健身</p>
                                </a>
                                <a href="{{ url('view/goods/list/cate/8') }}">
                                    <img src="{{ asset('img/cate-6.png') }}">
                                    <p>其他</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="blank" style="height: .3rem"></div>

    <div class="vm-push">
        <div class="vm-push-title">
            <img src="{{ asset('img/new.png') }}">
        </div>
        <div class="vm-push-content">
            <img src="{{ asset('img/ad-1.png') }}">
        </div>
    </div>

    <div class="vm-push">
        <div class="vm-push-title">
            <img src="{{ asset('img/hot.png') }}">
        </div>
        <div class="vm-push-content">
            @foreach ($goods as $element)
                <div class="vm-goods-box">
                    <a href="{{ url('view/goods') }}/{{ $element->id }}">
                    <img src="{{ url($element->goods_pic) }}">
                    <h3>{{ $element->goods_name }}</h3>
                    <span>{{ $element->title }}</span>
                    <p>{{ $element->goods_point }}cp <span>¥{{ $element->goods_price }}</span></p>
                    </a>
                </div>
            @endforeach
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


        function search() {
            var data = $('input[name=search]').val();
            location.href = '{{ url('view/goods/list') }}/search/'+data;
        }
    </script>
@endsection
