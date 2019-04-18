@extends('lib.home.header')
@section('body')
<style type="text/css">
    img {
        width: 100%;
    }
</style>
<ul>
    <li>
        <a href="{{ url('member0') }}">
            <img src="{{ asset('images/member_0.jpg') }}">
        </a>
    </li>
    <li>
        <a href="{{ url('member1') }}">
            <img src="{{ asset('images/member_1.jpg') }}">
        </a>
    </li>
    <li>
        <a href="{{ url('member2') }}">
            <img src="{{ asset('images/member_2.jpg') }}">
        </a>
    </li>
    <li>
        <a href="{{ url('member3') }}">
            <img src="{{ asset('images/member_3.jpg') }}">
        </a>
    </li>
    <li>
        <a href="{{ url('member4') }}">
            <img src="{{ asset('images/member_4.jpg') }}">
        </a>
    </li>
</ul>

<div class="nav-bottom" style="position: fixed;">
    <a href="{{ url('shop') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">首 页</span>
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

<script type="text/javascript">
</script>
@endsection






































