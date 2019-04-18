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
        background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        display: flex;
        justify-content: space-between;
        padding: 0 2rem;
    }
    .user_nav a {
        color: #FFF;
    }
    .bank_add {
        padding: 1rem;
    }
    ul {
        margin-top: .5rem;
        padding: 1rem;
        background: #FFF;
        text-align: center;
        box-shadow: 5px 6px 10px #888888;
    }
    ul li {
        line-height: 3rem;
        display: flex;
        justify-content: space-between;
    }
    ul li input[type="text"],input[type="number"] {
        border:none;
        border-bottom: 1px solid #CCC;
        border-radius: 0;
        width: 50%;
        text-align: center;
        color: #666;
        font-size: 1.2rem;
    }
    ul li span {
        padding-right: 1rem;
        padding-right: 1rem;
        font-size: 1.2rem;
    }
    ol {
         margin-top: 3rem;
        text-align: center;
    }
    ol span {
        background: #333;
        color: #FFF;
        padding: .5rem 3rem;
        font-size: 1rem;
    }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn" style="background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);margin: .7rem;padding: .2rem .5rem;"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a style="width: 4rem;"></a>
    <h3>兑 换 操 作</h3>
    <a href="{{ url('join/cash/log') }}" style="color:#EEE;">账户明细</a>
</div>
@if ($card->isNotEmpty())
    <div class="bank_add">
        <p style="color:#666;">进行兑换操作,请务必 <a href="{{ url('bank/create') }}">绑定银行卡</a></p>
        <ul>
            <li>
                <span>选择兑换项目: </span>
                <select id="type" style="height: auto;font-size: 1.2rem;" onchange="getBalance()">
                    <option value="0">== 请选择 ==</option>
                    <option value="3">梦享家收益-兑换</option>
                    <option value="4">债权收益-兑换</option>
                    <option value="5">产权收益-兑换</option>
                </select>
            </li>
            <li>
                <span>对应账户余额: </span>
                <b id="balance" style="color: #C40000;margin-right: 1.5rem;font-size: 1.2rem;"></b>
            </li>
            <li style="flex-wrap: wrap; margin: 0" class="goods_attr_info">
                <p style="font-size: 1.2rem;">请选择银行卡:</p>
                @foreach ($card as $element)
                    <input id="card{{ $element->id }}" value="{{ $element->id }}" type="radio" name="card" @if ($element->status == 1)
                        checked="checked"
                    @endif>
                    <label for="card{{ $element->id }}" style="width: 100%;line-height: 1rem;">
                        <img src="{{ $element->bank_logo }}">
                        <p>{{ $element->bank_card }}</p>
                    </label>
                @endforeach
                <li style="margin-top: 1rem;">
                    <span>输入兑换金额: </span>
                    <input type="number" id="price" placeholder="请输入兑换金额">
                </li>
                <p style="text-align: right;color: #666;margin-top: .5rem;"><i class="fa fa-info-circle"></i> 兑换金额必须为100的整数倍</p>
            </li>
        </ul>
        <ol><span onclick="sub()">提交操作</span></ol>
    </div>
@else
 <p style="color:#666;">进行兑换操作,请务必 <a href="{{ url('bank/create') }}">绑定银行卡</a></p>
@endif

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
        $.post('{{ url('join/getBalance') }}',{
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
            content: '请选择兑换的账户'
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
            content: '请输入需要兑换的金额'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        html = '<div><h3 style="height: 2.5rem;line-height: 2.5rem;font-size: 1.2rem;">兑换操作</h3><dl style="background: #ffff73;color: #C40000;padding: .5rem 2rem;"><dd>操作说明: <b>账户兑换</b></dd><dd>扣除账户CNY: <b>'+price+'</b></dd><dd style="    padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入支付密码: <input type="password" name="password"/></dd></dl><ol style="text-align: center;margin: 1rem;"><span onclick="add()" style="padding: .5rem 1rem;background: #C40000;color: #FFF;border-radius: 1rem;">确认操作</span></ol></div>';
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
            layer.closeAll();
            if (obj.status == 'success') {
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






































