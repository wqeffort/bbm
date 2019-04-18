@extends('lib.home.header')
@section('body')
<style>
    body {
        background: #EEE;
    }
    /*控制轮播进度条*/
    .focus {
        display: none;
    }
    /*控制侧边栏titile*/
    .mm-navbar-top {
        display: none;
    }
    .now {
        width: 3rem;
        height: 3rem;
        border:1px #cccccc solid;
        margin: 0 .5rem;
    }
    .now:focus {
        border:1px #587d18 solid;
    }
</style>
<!-- header Start -->
<header class="home-header">
    <ul>
        <li>
            <p class="index_menu"><a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a></p>
        </li>
        <li>
            <img src="{{ url('images/logo-text.png') }}" alt="">
        </li>
        <li>
            <p id="city"><i class="fa fa-map-marker"></i> 定位中</p>
        </li>
    </ul>
</header>
    <!-- header End -->

<!-- home Start -->
<div class="home" style="display: block; margin-bottom: 10rem;">
    <!-- banner Start -->
    <div style="height: 2.5rem"></div>
    <div class="inde_slider">
        <ul>
            @foreach ($banner as $element)
                <li>
                    <a href="{{ url($element->url) }}"><img src="{{ url($element->img) }}" alt="{{ $element->title }}"></a>
                </li>
            @endforeach
        </ul>
        <div class="search_box">
            <div class="search">
                <input type="text" name="search" placeholder="请输入商品名称或者关键字进行搜索！" />
                <i class="fa fa-search" aria-hidden="true"></i>
                <span onclick="search()">搜索</span>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('js/yxMobileSlider.js') }}"></script>
        <script type="text/javascript">
            $(".inde_slider").yxMobileSlider({width:640,height:300,during:3000})
        </script>
    </div>

 
    <div class="blank"></div>
    {{-- 广告一 --}}
    @if ($ad_1->status)
        <div class="ad_1">
            <a href="{{ url($ad_1->url) }}">
                <img src="{{ asset($ad_1->img) }}" alt="{{ $ad_1->title }}">
            </a>
        </div>
    @endif
    <div class="blank"></div>
{{-- 产品分类 --}}
    <div class="home_mod">
        <div class="home_mod_title">
            <h2>热门品类</h2>
            <p>HOT CATEGORIE</p>
        </div>
        <div class="home_mod_brand">
            <div class="index_brand">
                <ul>
                    <li>
                        <a href="{{ url('goods/list/brand/6') }}">
                            <img src="{{ url('images/brand-1.png') }}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('goods/list/brand/1') }}">
                            <img src="{{ url('images/brand-2.png') }}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('goods/list/brand/4') }}">
                            <img src="{{ url('images/brand-3.png') }}" alt="">
                        </a>
                    </li>
                </ul>
                <ul>
                    <li>
                        <a href="{{ url('goods/list/brand/2') }}">
                            <img src="{{ url('images/brand-4.png') }}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('goods/list/brand/3') }}">
                            <img src="{{ url('images/brand-5.png') }}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('rtsh') }}">
                            <img src="{{ url('images/brand-6.png') }}" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="blank"></div>

        <div class="home_mod_title">
            <h2>新品上架</h2>
            <p>HOT CATEGORIE</p>
        </div>
        @if ($ad_1->status)
            <div class="ad_1">
                <a href="{{ url($ad_2->url) }}">
                    <img src="{{ asset($ad_2->img) }}" alt="{{ $ad_2->title }}">
                </a>
            </div>
        @endif
        {{-- <div class="blank"></div> --}}
        <!-- New Goods Start -->
        <div class="newGoods">
            <!-- <h3>新货上架</h3>
            <ol>NEW PRODUCTS</ol> -->
            <div class="goods_box_rule">
                <div class="goods_box">
                    @if ($new->isNotEmpty())
                        @foreach ($new as $element)
                            <a href="{{ url('goods') }}/{{ $element->id }}">
                                <div class="goods_info">
                                    <img src="{{ url($element->goods_pic) }}" alt="{{ $element->goods_name }}">
                                    <h4>{{ $element->goods_name }}</h4>
                                    <p><i class="fa fa-yelp"></i><b> {{ $element->goods_point }} </b>积分</p>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <!-- New Goods End -->
        <div class="blank"></div>
        <div class="home_mod_title">
            <h2>热销产品</h2>
            <p>HOT CATEGORIE</p>
        </div>
        @if ($ad_3->status)
            <div class="ad_1">
                <a href="{{ url($ad_3->url) }}">
                    <img src="{{ asset($ad_3->img) }}" alt="{{ $ad_3->title }}">
                </a>
            </div>
        @endif
        <!-- New Goods Start -->
        <div class="hotGoods">
            <!-- <h3>热销产品</h3>
            <ol>HOT PRODUCTS</ol> -->
            @if ($hot)
                @foreach ($hot as $key => $element)
                    @if ($key == 0 || $key % 2 == 0)
                        <div class="goods_box">
                    @endif
                    <div class="goods_info">
                        <a href="{{ url('goods') }}/{{ $element->id }}">
                            <img src="{{ url($element->goods_pic) }}" alt="">
                            <h4>{{ $element->goods_name }}</h4>
                            <p><i class="fa fa-yelp"></i><b> {{ $element->goods_point }} </b>积分</p>
                            <ul>
                                <li>
                                    <span>{{-- 库存: <b>{{ $element->depot }}</b> 件 --}}</span>
                                </li>
                                <li>
                                    <span>
                                        <i class="fa fa-cart-arrow-down"></i> 现在购买
                                    </span>
                                </li>
                            </ul>
                        </a>
                    </div>
                    @if ($key != 0 && $key % 2 != 0)
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        <!-- New Goods End -->

        <div class="blank"></div>
        <div class="home_mod_title">
            <h2>猜你喜欢</h2>
            <p>LIKE CATEGORIE</p>
        </div>
        @if ($ad_4->status)
            <div class="ad_1">
                <a href="{{ url($ad_4->url) }}">
                    <img src="{{ asset($ad_4->img) }}" alt="{{ $ad_1->title }}">
                </a>
            </div>
        @endif
        <!-- Like Goods Start -->
        <div class="hotGoods">
            @if ($push)
                @foreach ($push as $key => $element)
                    @if ($key == 0 || $key % 2 == 0)
                        <div class="goods_box">
                    @endif
                    <div class="goods_info">
                        <a href="{{ url('goods') }}/{{ $element->id }}">
                            <img src="{{ url($element->goods_pic) }}" alt="">
                            <h4>{{ $element->goods_name }}</h4>
                            <p><i class="fa fa-yelp"></i><b> {{ $element->goods_point }} </b>积分</p>
                            <ul>
                                <li>
                                    <span>{{-- 库存: <b>{{ $element->depot }}</b> 件 --}}</span>
                                </li>
                                <li>
                                    <span>
                                        <i class="fa fa-cart-arrow-down"></i> 现在购买
                                    </span>
                                </li>
                            </ul>
                        </a>
                    </div>
                    @if ($key != 0 && $key % 2 != 0)
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        <!-- Like Goods End -->
    </div>

</div>
<!-- home End -->

<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>
<div class="blank"></div>

<div class="nav-bottom">
    <a class="nav-bottom-item true" ng-repeat="i in pages" href="">
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
var userInfoBox = '';
var inputCodeBox = '';
var makeCodeBox = '';
var fn = true;
    wx.ready(function () {
        // 获取用户坐标位置
        wx.getLocation({
            type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                lng = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                lat = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                if (res.errMsg == 'getLocation:ok') {
                    // 提交定位信息获取当前城市
                    $.post('{{ url('getReverseAds') }}',{
                        "_token" : '{{ csrf_token() }}',
                        "lat" : lat,
                        "lng" : lng,
                    },function (ret) {
                        var obj = $.parseJSON(ret);
                        console.log(obj);
                        if (obj.status == 'success') {
                            $("#city").html('<i class="fa fa-map-marker"></i> '+obj.data);
                        }else{
                            // 不执行任何操作,后期添加地区点选
                        }
                    })
                }else{
                    //提示
                    layer.open({
                        content: '请开启手机(GPS)定位'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        });
    });

    // 调取微信扫码
    function scanQrcode() {
        wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                alert(result);
                if (result) {
                    load();
                    // 发送AJAX获取登录权限
                    $.post('{{ url('admin/login') }}', {
                        '_token' : '{{ csrf_token() }}',
                        'key' : result,
                        'uuid' : '{{ session('user')->user_uuid }}'
                    }, function(ret) {
                        var obj = $.parseJSON(ret);
                        layer.close(loadBox);
                        if (obj.status == 'success') {
                            layer.open({
                                content: obj.msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
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
                    layer.open({
                        content: 'Sorry,通讯失败!未获取到二维码信息'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        });
    }

function search() {
    var data = $('input[name=search]').val();
    location.href = '{{ url('goods/list') }}/search/'+data;
}
</script>
@endsection
