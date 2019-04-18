@extends('lib.home.header')
@section('body')
<style type="text/css">
body {
    background: #EEE;
}
.extension dl {
    display: flex;
    justify-content: left;
    padding: 1rem;
    background: #FFF;
    position: relative;
}
.extension dl dd h3 {
    font-size: 1.3rem;
}
.extension dl dd p,h3 {
    line-height: 1.5rem;
}
.extension dl dd img {
    width: 4.5rem;
    border-radius: 50%;
    margin-right: 2rem;
}
.extension span {
    position: absolute;
    right: 2rem;
    top: 2rem;
    font-size: 2rem;
}
</style>
<div class="extension">
    <dl>
        <dd>
            <img src="{{ asset($user->user_pic) }}">
        </dd>
        <dd>
            <h3>{{ $user->user_nickname }}</h3>
            <p>我的积分: <b>{{ $user->user_point }}</b></p>
            <p>我的粉丝: <b>{{ $second->count() }}</b></p>
        </dd>
    </dl>
    <span onclick="makeQrcode()"><i class="fa fa-qrcode"></i></span>
</div>
<div class="nav-bottom">
    <a href="{{ url('shop') }}" class="nav-bottom-item false" ng-repeat="i in pages" >
        <i class="fa fa-th-large" aria-hidden="true"></i>
        <span class="nav-bottom-text ng-binding">服 务</span>
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

<script type="text/javascript">
    // 生成个人信息名片
    function makeQrcode() {
        $.get('{{ url('extension/getQrcode') }}',{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.open({
                    type: 1
                    ,content: '<div style="text-align: center;"></div>'
                    ,anim: 'up'
                    ,style: 'position:fixed; top:10%; left:10%; width: 80%; height: 80%; padding:10px 0; border:none;border-radius:.5rem;'
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
</script>

@endsection






































