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
        background: linear-gradient(20deg,#e4c800 0%,#e0af00 50%,#f57847 100%);
        background: -webkit-linear-gradient(20deg,#e4c800 0%,#e0af00 50%,#f57847 100%);
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
       justify-content: space-between;
       padding: 0 4rem;
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
    {{-- <a style="color: #FFF;" href="{{ url('recharge') }}">积分充值</a> --}}
    <a></a>
</div>
<div class="join_cell">
    <div class="nav">
        <div class="tab border-b">
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" class="curr">基本积分</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >赠送积分</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >开拓积分</a>
        </div>
        <div class="content">
            <ul>
                <li class="tet0" style="display: block">
                    <h3>基本积分余额: {{ $user->user_point }}</h3>
                    <div class="list">
                        @if ($log->isNotEmpty())
                            @foreach ($log as $value)
                                @if ($value->new_point)
                                    @if ($value->point != $value->new_point)
                                        <dl>
                                            <dd>
                                                <p>
                                                    @switch($value->type)
                                                        @case(1)
                                                            购物扣除积分
                                                            @break
                                                        @case(2)
                                                            春蚕添加积分
                                                            @break
                                                        @case(3)
                                                            会籍赠送积分
                                                            @break
                                                        @case(4)
                                                            充值到账积分
                                                            @break
                                                        @case(5)
                                                            活动返还积分
                                                            @break
                                                        @case(6)
                                                            年费扣除积分
                                                            @break
                                                        @case(7)
                                                            生日祝福
                                                            @break
                                                        @case(8)
                                                            开通会籍
                                                            @break
                                                        @case(10)
                                                            后台充值
                                                            @break
                                                        @case(11)
                                                            取消订单返款
                                                            @break
                                                        @case(17)
                                                            充值开拓积分
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
                                                        + {{ $value->new_point - $value->point}}
                                                    @else
                                                        - {{ $value->point - $value->new_point }}
                                                    @endif
                                                </p>
                                            </dd>
                                            <dd>
                                                <p>{{ $value->created_at }}</p>
                                                <p>结余: {{ $value->new_point }}</p>
                                            </dd>
                                        </dl>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                </li>
                <li class="tet1">
                    <h3>赠送积分余额: {{ $user->user_point_give }}</h3>
                    <div class="list">
                        @if ($log->isNotEmpty())
                            @foreach ($log as $value)
                                @if ($value->new_point_give)
                                    @if ($value->point_give != $value->new_point_give)
                                        <dl>
                                            <dd>
                                                <p>
                                                    @switch($value->type)
                                                        @case(1)
                                                            购物扣除积分
                                                            @break
                                                        @case(2)
                                                            春蚕添加积分
                                                            @break
                                                        @case(3)
                                                            会籍赠送积分
                                                            @break
                                                        @case(4)
                                                            充值到账积分
                                                            @break
                                                        @case(5)
                                                            活动返还积分
                                                            @break
                                                        @case(6)
                                                            年费扣除积分
                                                            @break
                                                        @case(7)
                                                            生日祝福
                                                            @break
                                                        @case(8)
                                                            开通会籍
                                                            @break
                                                        @case(10)
                                                            后台充值
                                                            @break
                                                        @case(11)
                                                            取消订单返款
                                                            @break
                                                        @case(17)
                                                            充值开拓积分
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
                                                        + {{ $value->new_point_give - $value->point_give}}
                                                    @else
                                                        - {{ $value->point_give - $value->new_point_give }}
                                                    @endif
                                                </p>
                                            </dd>
                                            <dd>
                                                <p>{{ $value->created_at }}</p>
                                                <p>结余: {{ $value->new_point_give }}</p>
                                            </dd>
                                        </dl>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                </li>
                <li class="tet2">
                    <h3>开拓积分余额: {{ $user->user_point_open }}</h3>
                    <div class="list">
                        @if ($log->isNotEmpty())
                            @foreach ($log as $value)
                                @if ($value->new_point_open)
                                    @if ($value->point_open != $value->new_point_open)
                                        <dl>
                                            <dd>
                                                <p>
                                                    @switch($value->type)
                                                        @case(1)
                                                            购物扣除积分
                                                            @break
                                                        @case(2)
                                                            春蚕添加积分
                                                            @break
                                                        @case(3)
                                                            会籍赠送积分
                                                            @break
                                                        @case(4)
                                                            充值到账积分
                                                            @break
                                                        @case(5)
                                                            活动返还积分
                                                            @break
                                                        @case(6)
                                                            年费扣除积分
                                                            @break
                                                        @case(7)
                                                            生日祝福
                                                            @break
                                                        @case(8)
                                                            开通会籍
                                                            @break
                                                        @case(10)
                                                            后台充值
                                                            @break
                                                        @case(11)
                                                            取消订单返款
                                                            @break
                                                        @case(17)
                                                            充值开拓积分
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
                                                        + {{ $value->new_point_open - $value->point_open}}
                                                    @else
                                                        - {{ $value->point_open - $value->new_point_open }}
                                                    @endif
                                                </p>
                                            </dd>
                                            <dd>
                                                <p>{{ $value->created_at }}</p>
                                                <p>结余: {{ $value->new_point_open }}</p>
                                            </dd>
                                        </dl>
                                    @endif
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


</script>
@endsection






































