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
        background: #333;
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
        justify-content: center;
    }
    ul li input[type="text"],input[type="number"] {
        border:none;
        border-bottom: 1px solid #CCC;
        border-radius: 0;
        width: 60%;
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
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>添加银行卡</h3>
    <span></span>
</div>
<div class="bank_add">
    <p style="color:#666;">请绑定持卡人本人的银行卡</p>
    <ul>
        <li>
            <span>持卡人: </span>
            <input type="text" value="{{ session('user')->user_name }}" name="name" disabled="true">
        </li>
        <li>
            <span>银行卡: </span>
            <input type="number" name="card" placeholder="请输入卡号">
        </li>
        <li>
            <span>开户行: </span>
            <input type="text" name="bankName" placeholder="请输入开户行支行">
        </li>
    </ul>
    <ol><span onclick="sub()">添加银行卡</span></ol>
</div>

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
function sub() {
    var name = $('input[name=name]').val();
    var card = $('input[name=card]').val();
    var bankName = $('input[name=bankName]').val();
    if (name == '') {
        layer.open({
            content: '持卡人不能为空,请先进行实名认证!'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (card == '') {
        layer.open({
            content: '银行卡号不能为空'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (bankName == '') {
        layer.open({
            content: '开户支行不能为空'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        load();
        $.post('{{ url('isBankCard') }}', {
            "_token" : '{{ csrf_token() }}',
            "name" : name,
            "card" : card
        }, function(ret) {
            layer.close(loadBox);
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                var html = '<ul style="box-shadow: none;"><li><img style="height: 3rem;" src="'+obj.data.logo+'" alt="'+obj.data.name+'" /><span>'+obj.data.name+'</span></li><li style="font-size: 1.2rem;">持卡人: <b>'+name+'</b></li><li style="font-size: 1.5rem;font-weight: bold;letter-spacing: .5rem;background: #DDD;">'+card+'</li></ul><ol style="margin-top:1.5rem;"><span onclick="add()">确认提交</span></ol>';
                layer.open({
                    type: 1
                    ,content: html
                    ,anim: 'up'
                    ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 250px; padding:10px 0; border:none;'
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
}

function add() {
    var name = $('input[name=name]').val();
    var card = $('input[name=card]').val();
    var bankName = $('input[name=bankName]').val();
    load();
    $.post('{{ url('bank/add') }}',{
        "_token" : '{{ csrf_token() }}',
        "name" : name,
        "card" : card,
        "bankName" : bankName
    },function (ret) {
        layer.close(loadBox);
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            setTimeout(function () {
                location.href = '{{ url('bank/list') }}';
            }, 1500)
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
</script>
@endsection






































