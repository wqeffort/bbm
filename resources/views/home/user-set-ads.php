@extends('lib.home.header')
@section('body')
<script type="text/javascript" src="{{ asset('js/Popt.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/city.json.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/citySet.js') }}"></script>
<script type="text/javascript">
    // 地区加载插件
$("#city").click(function (e) {
    SelCity(this,e);
});
$("s").click(function (e) {
    SelCity(document.getElementById("city"),e);
});
</script>
    <style type="text/css">
        dt {
            text-align: center;
            background: #C40000;
            color: #FFF;
            padding: .7rem 0;
            font-size: 1rem;
        }
        dd {
            margin: 1rem;
            border-bottom: 1px solid #999;
            display: flex;
            justify-content: space-between;
        }
        input {
            text-align: center;
            border:none;
            border-radius: none;
        }
    </style>

<dl>
    <dt>添加收货地址</dt>
    <dd>收件人姓名: <input style="border: none;width: 65%;" type="text" name="name" placeholder="请输入联系人姓名" value="{{ $name }}"></dd>
    <dd>联系电话: <input style="border: none;width: 65%;" type="number" name="phone" placeholder="请输入联系人电话"  value="{{ $phone }}"></dd>
    <dd>
        选择省市区:
        <input style="border: none;width: 65%;" class="input" name="city" id="city" type="text" placeholder="点击选择省市区" autocomplete="off" readonly="true"/><s></s>
    </dd>
    <dd>
        详细地址:
        <input style="border: none;width: 65%;" placeholder="请输入详细地址" type="text" name="ads">
    </dd>
</dl>
{{-- <ul>
    <li>
        <p>通讯地址设置:</p>
        <input type="text" value="{{ $user->ads }}" name="user_ads">
        <span>
            <i class="fa fa-info-circle" aria-hidden="true"></i> 该地址为用户通讯地址
        </span>
    </li>
    <li>
        <p>收货地址设置</p>
        <ul>
            @foreach ($ads as $key => $element)
                <li>
                    <input type="text" value="{{ $element->pro }}" name="ads_{{ $element->id }}">
                </li>
            @endforeach
        </ul>
    </li>
</ul> --}}


<div class="nav-bottom">
    <a href="{{ url('shop') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">服 务</span>
    </a>
    <!-- end ngRepeat: i in pages -->
    <a href="{{ url('article') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
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
<div id="la">
    <div id="gesturepwd" style="display: none"></div>
</div>
<script type="text/javascript">
    
</script>
@endsection






































