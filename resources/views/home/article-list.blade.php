@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #EEE;
    }
    a {
        color: #333;
    }
    .slider {
        position: relative;
    }
    .backBtn {
        position: absolute;
        left: 1rem;
        top: 1rem;
        background: rgba(0,0,0,.7);
        color: #FFF;
        border-radius: 50%;
        font-size: 1rem;
        height: 2.5rem;
        width: 2.5rem;
        line-height: 2.5rem;
        text-align: center;
    }
    .nav {
        padding: .5rem;
        text-align: center;
    }
    .nav ul {
        display: flex;
        justify-content: space-between;
    }
    .nav ul li {

    }
    .nav ul li img {
        width: 100%;
    }
    .nav ul li p {

    }
    .article {
        margin: .5rem;
        background: #FFF;
        padding: .5rem;
        /*border-radius: .5rem;*/
        margin-bottom: 1rem;
    }
    .article_img {
        width: 100%;
        /*border-radius: .5rem;*/
    }
    .article ul {
        display: flex;
        justify-content: space-between;
    }
    .article ul li {
        /*padding: 0 .5rem;*/
    }
    .article ul li img {
        width: 2rem;
        border-radius: 50%;
    }
    .article h3 {
        padding: .5rem;
    }
    .article dl {
        display: flex;
        justify-content: space-between;
        line-height: 2rem;
    }
    .article dl dd {
        padding: 0 .5rem;
    }
    .article dl dd i {
        font-size: 1.2rem;
    }
</style>
<!-- banner Start -->
    <div class="slider">
        <ul>
            @foreach ($ad as $element)
                <li>
                    <a href="{{ url($element->url) }}"><img src="{{ asset($element->img) }}" alt="{{ $element->title }}"></a>
                </li>
            @endforeach
        </ul>
        {{-- <div class="backBtn"><i class="fa fa-chevron-left"></i></div> --}}
        <!-- <div class="search_box">
            <div class="search">
                <input type="text" name="search1" placeholder="请输入商品名称或者关键字进行搜索！" onchange="search1()"/><i class="fa fa-search" aria-hidden="true"></i>
                </div>
        </div> -->
        <script type="text/javascript" src="{{ asset('js/yxMobileSlider.js') }}"></script>
        <script type="text/javascript">
            var height = $(window).height();
            var width = $(window).width();
            // 获取屏幕的高度
            $(".slider").yxMobileSlider({width:width,height:180,during:3000})
        </script>
    </div>
    <div class="nav">
        <ul>
            @foreach ($cate as $element)
                <a href="">
                    <li>
                        <img src="{{ url($element->pic) }}">
                    </li>
                </a>
            @endforeach
        </ul>
    </div>
    <div>
        @foreach ($article as $element)
        <a href="{{ url('article') }}/{{ $element->id }}">
            <div class="article">
                <img class="article_img" src="{{ asset($element->img) }}">
                <h3>{{ $element->title }}</h3>
                <ul>
                    <li>
                        <dl>
                            <dd>
                                <img src="http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLibbVMvILCmbf4tL791qHFB4QConhKVFsQEIMzKsbnGFmic6ib1f4wZKZI8PDtwhKLxFZvqxia2hwUYg/132">
                            </dd>
                            <dd>
                                <span>Author:<b>JaClub</b></span>
                            </dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dd>
                                <i class="fa fa-eye"></i> {{ $element->view }}
                            </dd>
                            <dd>
                                <i class="fa fa-thumbs-o-up"></i>
                            </dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </a>
    @endforeach
    </div>
    <div class="blank"></div>
    <div class="blank"></div>
    <div class="blank"></div>
    <div class="blank"></div>
    <div class="blank"></div>
    <div class="blank"></div>
    <div class="blank"></div>
    <div class="blank"></div>
<div class="nav-bottom">
    <a href="{{ url('shop') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">主 页</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('article') }}" class="nav-bottom-item true" ng-repeat="i in pages" >
        <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">资 讯</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('car') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">购物车</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('user') }}" class="nav-bottom-item false" ng-repeat="i in pages">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">我 的</span>
    </a>
</div>
@endsection






































