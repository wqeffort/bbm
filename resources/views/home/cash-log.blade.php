@extends('lib.home.header')
@section('body')
<style type="text/css">
    body {
        background: #EEE;
    }
    .join_head {
        height: 3rem;
        line-height: 3rem;
        text-align: center;
        color: #FFF;
        background: #d0a559;
        display: flex;
        justify-content: space-between;
        padding: 0 2rem;
    }
    .user_nav a {
        color: #FFF;
    }
    .log_list ul li {
        background: #FFF;
        margin: 1rem;
        border-left: 4px solid #d0a559;
        padding: 1rem;
    }
    .log_list ul li h3 {
        text-align: center;
        padding: 1rem 0;
        font-size: 1.2rem;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn" style="background: #8a671b;margin: .7rem;padding: .2rem .5rem;"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>兑 换 记 录</h3>
    <span style="color:#EEE;"> </span>
</div>

<div class="log_list">
    <ul>
        @foreach ($log as $value)
            <li>
                <h4><img src="{{ $value->bank_logo }}"></h4>
                <h3>{{ $value->price }} CNY</h3>
                {{-- <p>{{ $value->bank_name }} {{ $value->bank_location }}</p> --}}
                <p>收款卡号: <b>{{ $value->bank_card }}</b></p>
                <p>提现来源: <b>
                    @switch($value->type)
                        @case(1)
                            债权账户
                            @break
                        @case(2)
                            产权账户
                            @break
                        @default
                            未知账户
                    @endswitch
                    
                </b></p>
                <p>提现时间:<b> {{ $value->created_at }}</b></p>
                <p>提现状态:<b>
                    @switch($value->status)
                        @case(0)
                            暂未受理
                            @break
                        @case(1)
                            正在处理
                            @break
                        @case(2)
                            已经打款
                            @break
                        @default
                            未知状态
                    @endswitch
                    
                </b></p>
            </li>
        @endforeach
    </ul>
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
<script type="text/javascript">
function getBalance() {
    var type = $('#type option:selected').val();
    if (type != 0) {
        $.post('{{ url('getBalance') }}',{
            "_token" : '{{ csrf_token() }}',
            "type" : type
        },function (ret) {
            var obj = $.parseJSON(ret);
            console.log(obj);
            if (obj.status == 'success') {
                // 进行赋值操作
                $('#balance').text(obj.data+" CNY");
            }else{
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                setTimeout(function () {
                    location.reload();
                }, 1500)
            }
        })
    }
}

function sub() {
    var type = $('#type option:selected').val();
    var balance = $('#balance').text();
    var card = $("input[name='card']:checked").val();
    var price = $('#price').val();
    if (type == 0) {
        layer.open({
            content: '请选择提现的账户'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (balance == '') {
        layer.open({
            content: '请刷新页面重新选择账户查询余额'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (card == '') {
        layer.open({
            content: '请选择银行卡'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (price == '') {
        layer.open({
            content: '请输入需要提现的金额'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        html = '<div><h3 style="height: 2.5rem;line-height: 2.5rem;font-size: 1.2rem;">提现操作</h3><dl style="background: #ffff73;color: #C40000;padding: .5rem 2rem;"><dd>操作说明: <b>账户提现</b></dd><dd>扣除账户CNY: <b>'+price+'</b></dd><dd style="    padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入支付密码: <input type="password" name="password"/></dd></dl><ol style="text-align: center;margin: 1rem;"><span onclick="add()" style="padding: .5rem 1rem;background: #C40000;color: #FFF;border-radius: 1rem;">确认操作</span></ol></div>';
                layer.open({
                    type: 1
                    ,content: html
                    ,anim: 'up'
                    ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 200px; padding:10px 0; border:none;'
                });
    }
}

function add() {
    var type = $('#type option:selected').val();
    var balance = $('#balance').text();
    var card = $("input[name='card']:checked").val();
    var price = $('#price').val();
    var password = $('input[name=password]').val();
    load();
        $.post('{{ url('cash/add') }}', {
            "_token" : '{{ csrf_token() }}',
            "type" : type,
            "card" : card,
            "balance" : balance,
            "price" : price,
            "password" : password
        }, function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.closeAll();
                layer.open({
                    content: obj.msg
                    ,btn: '我知道了'
                });
            setTimeout(function () {
                location.href = '{{ url('user') }}';
            }, 5000)
            }else{
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        })
}
</script>
@endsection






































