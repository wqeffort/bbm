@extends('lib.home.header')
@section('body')
<style type="text/css">
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
.join_cent {
    color: #FFF;
    text-align: center;
    background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
    background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
    padding: 1rem;
    margin: 1rem;
}
a {
    color: #FFF;
}
dl {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 2rem;
}
dl dd {
    /*width: 50%;*/
}
dl dd p {
    font-size: 1rem;
    padding: .5rem 0;
}
h3 {
    font-weight: 400;
}
.join_btn {
    padding: .5rem 1rem;
}
.join_btn ul {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    text-align: center;
    margin-bottom: 1rem;
}
.join_btn ul li {
    box-shadow: 0 3px 10px #dbdbdf;
    border-radius: 1rem;
    padding: .5rem 1rem;
    color: #333;
}
.join_btn ul li img {
    width: 3.5rem;
    height: 3rem;
}
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a style="width: 4rem;"></a>
    @if ($join->type)
        <h3>合 伙 人</h3>
    @else
        <h3>加 盟 商</h3>
    @endif
    <a href="{{ url('join/log') }}">账户明细</a>
</div>
<div class="blank"></div>

@if ($join->type == 1)
    <div class="join_cent">
    <h3>账户积分余额</h3>
    <h2>{{ $join->point + $join->point_give + $join->point_fund}}</h2>
    <dl>
        <a href="{{ url('join/list/free') }}">
            <dd>
                <p>发展注册数</p>
                <b id="userCount">...加载中</b>
            </dd>
        </a>

        <a href="{{ url('join/list/pay') }}">
            <dd>
                <p>发展会员</p>
                <b>{{ $pay + $open }} 人</b>
            </dd>
        </a>

    </dl>
    <p style="border-bottom: 1px dashed #EEE;"></p>
    <dl>
        <dd onclick="list()">
            <p>发展合伙人</p>
            <b id="joinCount">...加载中</b>
        </dd>
        <dd>
            <p></p>
            <b></b>
        </dd>
    </dl>
</div>
@else
<div class="join_cent">
    <h3>账户积分余额</h3>
    <h2>{{ $join->point + $join->point_give + $join->point_fund + $join->point_open}}</h2>
    <dl>
        <a href="{{ url('join/list/free') }}">
            <dd>
                <p>非会员用户</p>
                <b>{{ $free }} 人</b>
            </dd>
        </a>
        <a href="{{ url('join/list/pay') }}">
            <dd>
                <p>会员用户</p>
                <b>{{ $pay }} 人</b>
            </dd>
        </a>
    </dl>
    <dl>
        <dd>
            <p>加盟商圈子</p>
            <b>数据稍后展示</b>
        </dd>
        <dd>
            <p>累计汇款</p>
            <b>{{ $sum }} 元</b>
        </dd>
    </dl>
</div>
@endif


@if ($join->type)
<div class="join_btn">
    <ul>
        <a href="{{ url('join/pay') }}">
            <li>
                <img src="{{ asset('images/join_btn_1.png') }}">
                <p>购买会籍</p>
            </li>
        </a>
        <a href="{{ url('join/recharge') }}">
            <li>
                <img src="{{ asset('images/join_btn_2.png') }}">
                <p>会员充值</p>
            </li>
        </a>
        <a href="{{ url('join/order') }}">
            <li>
                <img src="{{ asset('images/join_btn_3.png') }}">
                <p>订单管理</p>
            </li>
        </a>
    </ul>
    <ul>
        <a href="{{ url('join/asset') }}">
            <li>
                <img src="{{ asset('images/join_btn_4.png') }}">
                <p>资产管理</p>
            </li>
        </a>
        <a href="{{ url('sale/transfer') }}">
            <li>
                <img src="{{ asset('images/join_btn_10.png') }}">
                <p>积分转账</p>
            </li>
        </a>
        <a href="{{ url('sale/add') }}">
            <li>
                <img src="{{ asset('images/join_btn_11.png') }}">
                <p>合伙人</p>
            </li>
        </a>
    </ul>
    <ul>
        <a href="{{ url('sale/open') }}">
            <li>
                <img src="{{ asset('images/point_open.png') }}">
                <p>开拓积分</p>
            </li>
        </a>
        <a href="{{ url('join/set') }}">
            <li>
                <img src="{{ asset('images/join_btn_9.png') }}">
                <p>系统设置</p>
            </li>
        </a>
    </ul>
</div>
@else
<div class="join_btn">
    <ul>
        <a href="{{ url('join/pay') }}">
            <li>
                <img src="{{ asset('images/join_btn_1.png') }}">
                <p>购买会籍</p>
            </li>
        </a>
        <a href="{{ url('join/recharge') }}">
            <li>
                <img src="{{ asset('images/join_btn_2.png') }}">
                <p>会员充值</p>
            </li>
        </a>
        <a href="{{ url('join/order') }}">
            <li>
                <img src="{{ asset('images/join_btn_3.png') }}">
                <p>订单管理</p>
            </li>
        </a>
    </ul>
    <ul>
        <a href="{{ url('join/asset') }}">
            <li>
                <img src="{{ asset('images/join_btn_4.png') }}">
                <p>资产管理</p>
            </li>
        </a>
        <a href="{{ url('join/card') }}">
            <li>
                <img src="{{ asset('images/join_btn_5.png') }}">
                <p>推广名片</p>
            </li>
        </a>
        <a href="">
            <li>
                <img src="{{ asset('images/join_btn_6.png') }}">
                <p>营收统计</p>
            </li>
        </a>
    </ul>
    <ul>
        {{-- <a href="">
            <li>
                <img src="{{ asset('images/join_btn_7.png') }}">
                <p>资料管理</p>
            </li>
        </a> --}}
        @if ($join->protocol == 2)
            <a href="{{ url('spring/asset') }}">
        @else
            <a href="javascript:;" onclick="read()">
        @endif
            <li>
                <img src="{{ asset('images/join_btn_8.png') }}">
                <p>春蚕计划</p>
            </li>
        </a>

        <a href="{{ url('join/set') }}">
            <li>
                <img src="{{ asset('images/join_btn_9.png') }}">
                <p>系统设置</p>
            </li>
        </a>
    </ul>
</div>
@endif
<script type="text/javascript">
function read() {
    layer.open({
        content: '您还不是春蚕用户!'
        ,btn: '我知道了'
      });
}
var box = ''
var mea = ''
// 检查是否修改过初始密码
// window.onload = function () {
//     var pw = '{{ $pw }}'
//     // alert(pw)
//     if (pw) {
//         layer.open({
//             content: '你的密码为初始密码,请问需要进行修改吗?'
//             ,btn: ['现在修改', '暂不修改']
//             ,yes: function(index){
//               load();
//                 $.post('{{ url('join/set/password') }}',{
//                     "_token" : '{{ csrf_token() }}'
//                 },function (ret) {
//                     var obj = $.parseJSON(ret);
//                     layer.close(loadBox);
//                     if (obj.status == 'success') {
//                         box = layer.open({
//                             type: 1
//                             ,content: '<div style="text-align:center;"><h3>请输入手机验证码</h3><ul><li style="line-height: 2rem;height: 2rem;margin: 0px 2rem;">新的密码: <input type="password" name="password" /></li><li style="line-height: 2rem;height: 2rem;margin: 0px 2rem;">确认密码: <input type="password" name="password_again" /></li><li style="line-height: 2rem;height: 2rem;margin: 0px 2rem;">验 证 码: <input type="number" placeholder="四位数验证码" name="code" style="text-align:center;"/></li></ul><p style="text-align:right;margin: 1rem;">验证码已经发送至 <b style="color:#000;">'+obj.data+'</b></p><div style="margin-top: 2rem;"><span style="padding: .5rem 1rem;background: #C40000;color: #FFF;" onclick="sub()">确定提交</span></div></div>'
//                             ,anim: 'up'
//                             ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 320px; padding:10px 0; border:none;'
//                         });
//                     }else{
//                         layer.open({
//                             content: obj.msg
//                             ,skin: 'msg'
//                             ,time: 2 //2秒后自动关闭
//                         });
//                     }
//                 })
//             }
//         })
//     }
// }

function sub() {
        var password = $('input[name=password]').val();
        var password_again = $('input[name=password_again]').val();
        var code = $('input[name=code]').val();
        if (password == "" || password_again == "") {
            layer.open({
                content: '重置的新密码不能为空!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else if (password != password_again) {
            layer.open({
                content: '两次输入的密码不一致!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else if (code == '') {
            layer.open({
                content: '验证码不能为空!'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            layer.close(mea);
            load();
            $.post('{{ url('join/set/password/update') }}', {
                "_token" : '{{ csrf_token() }}',
                "password" : password,
                "code" : code
            }, function(ret) {
                layer.close(loadBox);
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    layer.close(box);
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
        }
    }


$(function () {
    var top = '{{ session('join')->uuid }}';
    // 获取发展的用户数量
    if (top == '596A043D-664B-5A5A-7F54-3C74B9E332F6') {
        console.log(1);
        $.post('{{ url('sale/get/user/count/top') }}',{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                $('#userCount').html(obj.data+" 人")
            }else{
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
    }else{
        $.post('{{ url('sale/get/user/count') }}',{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                $('#userCount').html(obj.data+" 人")
            }else{
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
    }

    // 获取发展的用户数量
    $.post('{{ url('sale/get/sale/count') }}',{
        "_token" : '{{ csrf_token() }}'
    },function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            $('#joinCount').html(obj.data+" 人")
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    });
})

function list() {
    var uuid = '{{ session('join')->uuid }}';
    if (uuid) {
        location.href="{{ url('sale/pid/saleList') }}/"+uuid;
    }else{
        location.href="{{ url('join') }}"
    }
}

</script>
@endsection






































