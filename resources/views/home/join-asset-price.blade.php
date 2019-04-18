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
    .join_cell ul {
        padding: 1rem;
    }
    .list dl {
        padding: .5rem;
        border-left: 4px solid #27c7fe;
        margin-bottom: 1rem;
        line-height: 1.5rem;
        background: #FFF;
        position: relative;
    }
    .list dl dd {
        display: flex;
        justify-content: space-between;
        line-height: 2rem;
    }
    h4 {
        font-size: 1rem;
    }
    .join_cell ul li span {
        position: absolute;
        right: 2rem;
        top: 1rem;
        font-size: 1.5rem;
    }
      .border-b {
       position: relative;
      }
      .border-b:after {
       top: auto;
       bottom: 0;
      }
      .border-t:before, .border-b:after {
       content: '';
       position: absolute;
       left: 0;
       background: #ddd;
       right: 0;
       height: 1px;
       -webkit-transform: scaleY(0.5);
       transform: scaleY(0.5);
       -webkit-transform-origin: 0 0;
       transform-origin: 0 0;
      }
      .nav {
       background-color: #fff;
       text-align: center;
      }
      .nav .tab {
       position: relative;
       overflow: hidden;
       display: flex;
       justify-content: space-between;
       padding: 0 2rem;
}
      .tab a {
       height: 2.56rem;
       line-height:2.56rem;
       display: inline-block;
       /*border-right: 1px solid #e1e1e1;*/
      }
      .tab a:last-child {
       border-right: 0;
      }
      .tab .curr {
       border-bottom: 2px solid #fc7831;
       color: #fc7831;
      }
      .content ul li {
       display: none;
       padding: 3%;
       width: 94%;
       position: relative;
      }
</style>
<div class="user_nav" style="width: 10rem;">
    <a href="#leftMenuBtn"><i class="fa fa-bars" aria-hidden="true"></i> 菜单</a>
</div>
<div class="join_head">
    <a></a>
    <h3>账户积分明细</h3>
    <span onclick="searchBtn()" style="font-size: 1.2rem;"></span>
</div>
<div class="join_cell">
    <div class="nav">
        <div class="tab border-b">
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" class="curr">梦享家收益</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >债权收益</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >债权明细</a>
        </div>
        <div class="content">
            <ul>
                <li class="tet0" style="display: block">
                    <h3>梦享家收益: {{ $join->join_cash }}</h3>
                    <div class="list">
                        @if ($log->isNotEmpty())
                            @foreach ($log as $value)
                                @if ($value->join_cash && $value->new_join_cash)
                                <dl>
                                    <dd>
                                        <p>
                                            @switch($value->type)
                                                @case(1)
                                                    加盟商返佣积分转现
                                                    @break
                                                @case(2)
                                                    债权收益
                                                    @break
                                                @case(3)
                                                    兑换操作
                                                    @break
                                                @case(4)
                                                    兑换失败,返还账户
                                                    @break
                                                @case(5)
                                                    转入融通四海冻结账户
                                                    @break
                                                @default
                                                    未知渠道
                                            @endswitch
                                        </p>
                                        <p style="@if ($value->add == 1)
                                            color:#C40000;
                                        @else
                                            color:green;
                                        @endif">
                                            @if ($value->add == 1)
                                                + {{ $value->new_join_cash - $value->join_cash}}
                                            @else
                                                - {{ $value->join_cash - $value->new_join_cash }}
                                            @endif
                                        </p>
                                    </dd>
                                    <dd>
                                        <p>{{ $value->created_at }}</p>
                                        <p>结余: {{ $value->new_join_cash }}</p>
                                    </dd>
                                </dl>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </li>
                <li class="tet1">
                    <h3>债权收益: {{ $join->rtsh_bond }}</h3>
                    <div class="list">
                        @if ($log->isNotEmpty())
                            @foreach ($log as $value)
                                @if ($value->rtsh_bond && $value->new_rtsh_bond)
                                <dl>
                                    <dd>
                                        <p>
                                            @switch($value->type)
                                                @case(1)
                                                    加盟商返佣积分转现
                                                    @break
                                                @case(2)
                                                    债权收益
                                                    @break
                                                @case(3)
                                                    兑换操作
                                                    @break
                                                @case(4)
                                                    兑换失败,返还账户
                                                    @break
                                                @case(5)
                                                    转入融通四海冻结账户
                                                    @break
                                                @default
                                                    未知渠道
                                            @endswitch
                                        </p>
                                        <p style="@if ($value->add == 1)
                                            color:#C40000;
                                        @else
                                            color:green;
                                        @endif">
                                            @if ($value->add == 1)
                                                + {{ $value->new_rtsh_bond - $value->rtsh_bond}}
                                            @else
                                                - {{ $value->rtsh_bond - $value->new_rtsh_bond }}
                                            @endif
                                        </p>
                                    </dd>
                                    <dd>
                                        <p>{{ $value->created_at }}</p>
                                        <p>结余: {{ $value->new_rtsh_bond }}</p>
                                    </dd>
                                </dl>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </li>
                <li  class="tet2">
                    <h3>债权明细: {{ $join->rtsh_bond }}</h3>
                    <div class="list">
                        @if ($rtsh_log->isNotEmpty())
                            @foreach ($rtsh_log as $value)
                                @if ($value->rtsh_bond && $value->new_rtsh_bond)
                                <dl>
                                    <dd>
                                        <p>投资用户</p>
                                        <p>{{ $value->user_name }}</p>
                                    </dd>
                                    <dd>
                                        <p>订单编号</p>
                                        <p>{{ $value->num }}</p>
                                    </dd>
                                    <dd>
                                        <p>订单详情</p>
                                        <p>总价:{{ $value->price }}--{{ $value->time }}期</p>
                                    </dd>
                                    <dd>
                                        <p>债权收益</p>
                                        <p style="color:#C40000;">
                                            {{ $value->new_rtsh_bond - $value->rtsh_bond}}
                                        </p>
                                    </dd>
                                    <dd>
                                        <p>{{ $value->updated_at }}</p>
                                        <p>结余: {{ $value->new_rtsh_bond }}</p>
                                    </dd>
                                </dl>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $(".tab a").click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        var index = $(this).index();
        number = index;
        $('.nav .content ul li').hide();
        // $('.nav .content ul li:eq(' + number + ')').show();
        if (index == 0) {
            $('.tet0').css('display','block');
        }else if (index == 1) {
            $('.tet1').css('display','block');
        }else if (index == 2) {
            $('.tet2').css('display','block');
        }
    });
})
function changeCash() {
    layer.open({
        type: 1
        ,content: '<div><ul><h3 style="text-align:center;">转现操作</h3></ul><dl style="background: #ffff73;color: #C40000;padding: .5rem 2rem;margin: 0 1rem;"><dd>操作说明: <b>转换返佣到收益(现金)账户</b><p>当前转换比例为1 : 0.8</p><p>转现成功后,请到梦享家收益账户查询</p><p>转现金额必须为100的整数</p></dd><dd style="    padding: .5rem 0;border-top: 1px dashed #CCC;margin-top: .5rem;">请输入支付密码: <input type="password" name="password"/></dd></dl><p style="text-align: center;margin: 2rem 0;">转现金额: <input type="number" name="point_fund" placeholder="请输入100的倍数" /></p> <ol style="text-align: center;margin: 1rem;"><span onclick="sub()" style="padding: .5rem 2rem;background: #C40000;color: #FFF;border-radius: 1rem;">确认操作</span></ol></div>'
        ,anim: 'up'
        ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 350px; padding:0; border:none;text-align:center;'
    });
}

function sub() {
    load();
    var password = $('input[name=password]').val();
    var point_fund = $('input[name=point_fund]').val();
    if (password == '') {
        layer.open({
            content: '请输入兑换密码!'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else if (point_fund == '') {
        layer.open({
            content: '请输入转现的积分数!'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        $.post('{{ url('join/asset/changeCash') }}', {
            "_token" : '{{ csrf_token() }}',
            "point_fund" : point_fund,
            "password" : password
        }, function(ret) {
            layer.closeAll()
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.open({
                    content: obj.msg
                    ,btn: '我知道了'
                    ,yes: function(index){
                        location.reload();
                        layer.close(index);
                    }
                });

            }else{
                layer.open({
                    content: obj.msg
                    ,btn: '我知道了'
                });
            }
        });
    }
}
</script>
@endsection






































