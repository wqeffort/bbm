@extends('lib.admin.header')
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
    .log_cell ul {
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
    .log_cell ul li span {
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
       width: 94%;
       position: relative;
      }

      .content_join_point ul li {
       display: none;
       width: 94%;
       position: relative;
      }
      .content_join_price ul li {
       display: none;
       width: 94%;
       position: relative;
      }
</style>
<div class="log_cell">
    <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <b>用户积分流水</b>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
    <div class="nav">
        <div class="tab border-b user_tab">
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" class="curr">基本积分</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >赠送积分</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >开拓积分</a>
        </div>
        <div class="content">
            <ul>
                <li class="tet0" style="display: block">
                    <h3>基本积分余额: {{ $user->user_point }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($userPointLog->isNotEmpty())
                                        @foreach ($userPointLog as $value)
                                        @if ($value->point != $value->new_point && $value->point || $value->new_point)
                                        <tr>
                                            <td class="tc">{{ $value->id }}</td>
                                            <td class="tc">
                                                @switch($value->type)
                                                    @case(1)
                                                        商城购物
                                                        @break
                                                    @case(2)
                                                        春蚕创业基金(积分)
                                                        @break
                                                    @case(3)
                                                        加盟商为用户开通会籍送积分
                                                        @break
                                                    @case(4)
                                                        加盟商为用户充值的积分
                                                        @break
                                                    @case(5)
                                                        活动赠送积分
                                                        @break
                                                    @case(6)
                                                        扣除年费积分
                                                        @break
                                                    @case(7)
                                                        生日充值
                                                        @break
                                                    @case(8)
                                                        后台开通会籍
                                                        @break
                                                    @case(9)
                                                        后台开通会籍
                                                        @break
                                                    @case(10)
                                                        后台充值
                                                        @break
                                                    @case(11)
                                                        商城订单取消返款
                                                        @break
                                                    @case(12)
                                                        后台帮助下单
                                                        @break
                                                    @case(13)
                                                        后台操作扣除
                                                        @break
                                                    @case(14)
                                                        系统返还
                                                        @break
                                                    @default
                                                        未知渠道
                                                @endswitch
                                            </td>
                                            <td class="tc">{{ $value->point }}</td>
                                            <td class="tc">{{ $value->new_point }}</td>
                                            <td class="tc">
                                                <p style="
                                                @if ($value->add == 1)
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
                                            </td>
                                            <td class="tc">
                                                {{ $value->created_at }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </li>
                <li class="tet1">
                    <h3>赠送积分余额: {{ $user->user_point_give }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($userPointLog->isNotEmpty())
                                        @foreach ($userPointLog as $value)
                                        @if ($value->point_give != $value->new_point_give && $value->point_give || $value->new_point_give)
                                            <tr>
                                                <td class="tc">{{ $value->id }}</td>
                                                <td class="tc">
                                                    @switch($value->type)
                                                        @case(1)
                                                            商城购物
                                                            @break
                                                        @case(2)
                                                            春蚕创业基金(积分)
                                                            @break
                                                        @case(3)
                                                            加盟商为用户开通会籍送积分
                                                            @break
                                                        @case(4)
                                                            加盟商为用户充值的积分
                                                            @break
                                                        @case(5)
                                                            活动赠送积分
                                                            @break
                                                        @case(6)
                                                            扣除年费积分
                                                            @break
                                                        @case(7)
                                                            生日充值
                                                            @break
                                                        @case(9)
                                                            后台开通会籍
                                                            @break
                                                        @case(10)
                                                            后台充值
                                                            @break
                                                        @case(11)
                                                            商城订单取消返款
                                                            @break
                                                        @default
                                                            未知渠道
                                                    @endswitch
                                                </td>
                                                <td class="tc">{{ $value->point_give }}</td>
                                                <td class="tc">{{ $value->new_point_give }}</td>
                                                <td class="tc">
                                                    <p style="
                                                    @if ($value->add == 1)
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
                                                </td>
                                                <td class="tc">
                                                    {{ $value->created_at }}
                                                </td>
                                            </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                <li  class="tet2">
                    <h3>开拓积分余额: {{ $user->user_point_open }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($userPointLog->isNotEmpty())
                                        @foreach ($userPointLog as $value)
                                        @if ($value->point_open != $value->new_point_open)
                                            <tr>
                                                <td class="tc">{{ $value->id }}</td>
                                                <td class="tc">
                                                    @switch($value->type)
                                                        @case(1)
                                                            绑定添加积分
                                                            @break
                                                        @case(2)
                                                            分享添加积分
                                                            @break
                                                        @case(3)
                                                            充值积分
                                                            @break
                                                        @case(4)
                                                            开通会籍
                                                            @break
                                                        @case(5)
                                                            销售会籍
                                                            @break
                                                        @case(6)
                                                            销售积分
                                                            @break
                                                        @case(7)
                                                            开通加盟商
                                                            @break
                                                        @case(9)
                                                            返佣积分转现
                                                            @break
                                                        @case(10)
                                                            推荐春蚕
                                                            @break
                                                        @case(11)

                                                        @default
                                                            未知渠道
                                                    @endswitch
                                                </td>
                                                <td class="tc">{{ $value->point_open }}</td>
                                                <td class="tc">{{ $value->new_point_open }}</td>
                                                <td class="tc">
                                                    <p style="
                                                    @if ($value->add == 1)
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
                                                </td>
                                                <td class="tc">
                                                    {{ $value->created_at }}
                                                </td>
                                            </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    @if ($isJoin)
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <b>加盟商积分流水</b>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
        <div class="nav">
        <div class="tab border-b join_tab_point">
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" class="curr">基本积分</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >赠送积分</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >返佣积分</a>
        </div>
        <div class="content_join_point">
            <ul>
                <li class="join0" style="display: block">
                    <h3>基本积分余额: {{ $join->point }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($joinPointLog->isNotEmpty())
                                        @foreach ($joinPointLog as $value)
                                        @if ($value->point != $value->new_point && $value->point || $value->new_point)
                                        <tr>
                                            <td class="tc">{{ $value->id }}</td>
                                            <td class="tc">
                                                @switch($value->type)
                                                    @case(1)
                                                        绑定添加积分
                                                        @break
                                                    @case(2)
                                                        分享添加积分
                                                        @break
                                                    @case(3)
                                                        充值积分
                                                        @break
                                                    @case(4)
                                                        开通会籍
                                                        @break
                                                    @case(5)
                                                        销售会籍
                                                        @break
                                                    @case(6)
                                                        销售积分
                                                        @break
                                                    @case(7)
                                                        开通加盟商
                                                        @break
                                                    @case(9)
                                                        返佣积分转现
                                                        @break
                                                    @default
                                                        未知渠道
                                                @endswitch
                                            </td>
                                            <td class="tc">{{ $value->point }}</td>
                                            <td class="tc">{{ $value->new_point }}</td>
                                            <td class="tc">
                                                <p style="
                                                @if ($value->add == 1)
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
                                            </td>
                                            <td class="tc">
                                                {{ $value->created_at }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </li>
                <li class="join1">
                    <h3>赠送积分余额: {{ $join->point_give }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($joinPointLog->isNotEmpty())
                                        @foreach ($joinPointLog as $value)
                                        @if ($value->point_give != $value->new_point_give && $value->point_give || $value->new_point_give)
                                            <tr>
                                                <td class="tc">{{ $value->id }}</td>
                                                <td class="tc">
                                                    @switch($value->type)
                                                        @case(1)
                                                            绑定添加积分
                                                            @break
                                                        @case(2)
                                                            分享添加积分
                                                            @break
                                                        @case(3)
                                                            充值积分
                                                            @break
                                                        @case(4)
                                                            开通会籍
                                                            @break
                                                        @case(5)
                                                            销售会籍
                                                            @break
                                                        @case(6)
                                                            销售积分
                                                            @break
                                                        @case(7)
                                                            开通加盟商
                                                            @break
                                                        @case(9)
                                                            返佣积分转现
                                                            @break
                                                        @case(10)
                                                            推荐春蚕
                                                            @break
                                                        @case(11)

                                                        @default
                                                            未知渠道
                                                    @endswitch
                                                </td>
                                                <td class="tc">{{ $value->point_give }}</td>
                                                <td class="tc">{{ $value->new_point_give }}</td>
                                                <td class="tc">
                                                    <p style="
                                                    @if ($value->add == 1)
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
                                                </td>
                                                <td class="tc">
                                                    {{ $value->created_at }}
                                                </td>
                                            </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                <li  class="join2">
                    <h3>返佣积分余额: {{ $join->point_fund }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($joinPointLog->isNotEmpty())
                                        @foreach ($joinPointLog as $value)
                                        @if ($value->point_fund != $value->new_point_fund && $value->point_fund || $value->new_point_fund)
                                            <tr>
                                                <td class="tc">{{ $value->id }}</td>
                                                <td class="tc">
                                                    @switch($value->type)
                                                        @case(1)
                                                            绑定添加积分
                                                            @break
                                                        @case(2)
                                                            分享添加积分
                                                            @break
                                                        @case(3)
                                                            充值积分
                                                            @break
                                                        @case(4)
                                                            开通会籍
                                                            @break
                                                        @case(5)
                                                            销售会籍
                                                            @break
                                                        @case(6)
                                                            销售积分
                                                            @break
                                                        @case(7)
                                                            开通加盟商
                                                            @break
                                                        @case(9)
                                                            返佣积分转现
                                                            @break
                                                        @case(10)
                                                            推荐春蚕
                                                            @break
                                                        @case(11)
                                                            推荐人累计打款50万
                                                            @break
                                                        @case(12)
                                                            推荐人打款到公司
                                                            @break
                                                        @default
                                                            未知渠道
                                                    @endswitch
                                                </td>
                                                <td class="tc">{{ $value->point_fund }}</td>
                                                <td class="tc">{{ $value->new_point_fund }}</td>
                                                <td class="tc">
                                                    <p style="
                                                    @if ($value->add == 1)
                                                        color:#C40000;
                                                    @else
                                                        color:green;
                                                    @endif">
                                                        @if ($value->add == 1)
                                                            + {{ $value->new_point_fund - $value->point_fund}}
                                                        @else
                                                            - {{ $value->point_fund - $value->new_point_fund }}
                                                        @endif
                                                    </p>
                                                </td>
                                                <td class="tc">
                                                    {{ $value->created_at }}
                                                </td>
                                            </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <b>加盟商资金流水</b>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
    <div class="nav">
        <div class="tab border-b join_tab_price">
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" class="curr">梦享家收益</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >债券收益</a>
            @if ($isSpring)
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >春蚕流水</a>
            @endif
        </div>
        <div class="content_join_price">
            <ul>
                <li class="join3" style="display: block">
                    <h3>梦享家收益余额: {{ $join->join_cash }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($joinPriceLog->isNotEmpty())
                                        @foreach ($joinPriceLog as $value)
                                        @if ($value->join_cash != $value->new_join_cash && $value->join_cash || $value->new_join_cash)
                                        <tr>
                                            <td class="tc">{{ $value->id }}</td>
                                            <td class="tc">
                                                @switch($value->type)
                                                        @case(1)
                                                            加盟商返佣积分转现
                                                            @break
                                                        @case(2)
                                                            融通四海加盟商债权提成
                                                            @break
                                                        @case(3)
                                                            提现
                                                            @break
                                                        @case(4)
                                                            提现失败,系统返还
                                                            @break
                                                        @case(5)
                                                            融通四海债权调动
                                                            @break
                                                        @default
                                                            未知渠道
                                                @endswitch
                                            </td>
                                            <td class="tc">{{ $value->join_cash }}</td>
                                            <td class="tc">{{ $value->new_join_cash }}</td>
                                            <td class="tc">
                                                <p style="
                                                @if ($value->add == 1)
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
                                            </td>
                                            <td class="tc">
                                                {{ $value->created_at }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </li>
                <li class="join4">
                    <h3>债权收益余额: {{ $join->rtsh_bond }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($joinPriceLog->isNotEmpty())
                                        @foreach ($joinPriceLog as $value)
                                        @if ($value->rtsh_bond != $value->new_rtsh_bond && $value->rtsh_bond || $value->new_rtsh_bond)
                                            <tr>
                                                <td class="tc">{{ $value->id }}</td>
                                                <td class="tc">
                                                    @switch($value->type)
                                                        @case(1)
                                                            加盟商返佣积分转现
                                                            @break
                                                        @case(2)
                                                            融通四海加盟商债权提成
                                                            @break
                                                        @case(3)
                                                            提现
                                                            @break
                                                        @case(4)
                                                            提现失败,系统返还
                                                            @break
                                                        @case(5)
                                                            融通四海债权调动
                                                            @break
                                                        @case(6)
                                                            后台扣除
                                                            @break
                                                        @default
                                                            未知渠道
                                                    @endswitch
                                                </td>
                                                <td class="tc">{{ $value->rtsh_bond }}</td>
                                                <td class="tc">{{ $value->new_rtsh_bond }}</td>
                                                <td class="tc">
                                                    <p style="
                                                    @if ($value->add == 1)
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
                                                </td>
                                                <td class="tc">
                                                    {{ $value->created_at }}
                                                </td>
                                            </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </li>
                @if ($isSpring)
                <li class="join5">
                    <h3>春蚕创业基金余额: {{ $join->price }}</h3>
                    <form action="#" method="post">
                        <div class="result_wrap">
                            <div class="result_content">
                                <table class="list_tab">
                                    <tr>
                                        <th class="tc">日志ID</th>
                                        <th class="tc">变动描述</th>
                                        <th class="tc">变动之前</th>
                                        <th class="tc">变动之后</th>
                                        <th class="tc">变动金额</th>
                                        <th class="tc">变动时间</th>
                                    </tr>
                                    @if ($springLog->isNotEmpty())
                                        @foreach ($springLog as $value)
                                            <tr>
                                                <td class="tc">{{ $value->id }}</td>
                                                <td class="tc">
                                                    @switch($value->type)
                                                        @case(1)
                                                            创业基金发放(春蚕账户)
                                                            @break
                                                        @case(2)
                                                            积分发放(个人账户)
                                                            @break
                                                        @case(3)
                                                            提现
                                                            @break
                                                        @case(4)
                                                            提现失败,系统返还
                                                            @break
                                                        @case(5)
                                                            旧系统提现扣除(已打款)
                                                            @break
                                                        @default
                                                            未知渠道
                                                    @endswitch
                                                </td>
                                                <td class="tc">{{ $value->point }}</td>
                                                <td class="tc">{{ $value->new_point }}</td>
                                                <td class="tc">
                                                    <p style="
                                                    @if ($value->add == 1)
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
                                                </td>
                                                <td class="tc">
                                                    {{ $value->created_at }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </li>
                @endif
            </ul>
        </div>
    </div>
    @else
    该用户不是加盟商
    @endif
    <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <b>用户提现日志</b>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
    <form action="#" method="post">
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">日志ID</th>
                        <th class="tc">变动描述</th>
                        <th class="tc">提现金额</th>
                        <th class="tc">提现状态</th>
                        <th class="tc">出款账户</th>
                        <th class="tc">日志记录</th>
                        <th class="tc">审核人员</th>
                        <th class="tc">提现时间</th>
                        <th class="tc">处理时间</th>
                    </tr>
                    @if ($cashLog->isNotEmpty())
                        @foreach ($cashLog as $value)
                            <tr>
                                <td class="tc">{{ $value->id }}</td>
                                <td class="tc">
                                    @switch($value->type)
                                        @case(1)
                                            债权提现
                                            @break
                                        @case(2)
                                            产权提现
                                            @break
                                        @case(3)
                                            梦享家现金账户提现
                                            @break
                                        @case(4)
                                            加盟商债权提现
                                            @break
                                        @case(5)
                                            加盟商产权提现
                                            @break
                                        @case(6)
                                            春蚕提现
                                            @break
                                        @default
                                            未知渠道
                                    @endswitch
                                </td>
                                <td class="tc">{{ $value->price }}</td>
                                <td class="tc">
                                    @switch($value->status)
                                        @case(0)
                                            暂未受理
                                            @break
                                        @case(1)
                                            已经受理
                                            @break
                                        @case(2)
                                            已经打款
                                            @break
                                        @default
                                            未知状态
                                    @endswitch
                                </td>
                                <td class="tc">{{ $value->img }}</td>
                                <td class="tc">{{ $value->log }}</td>
                                <td class="tc">{{ $value->user_name }}</td>
                                <td class="tc">
                                    {{ $value->created_at }}
                                </td>
                                <td class="tc">
                                    {{ $value->updated_at }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
$(function() {
    $(".user_tab a").click(function() {
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

$(function() {
    $(".join_tab_point a").click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        var index = $(this).index();
        number = index;
        $('.nav .content_join_point ul li').hide();
        // $('.nav .content ul li:eq(' + number + ')').show();
        if (index == 0) {
            $('.join0').css('display','block');
        }else if (index == 1) {
            $('.join1').css('display','block');
        }else if (index == 2) {
            $('.join2').css('display','block');
        }
    });
})

$(function() {
    $(".join_tab_price a").click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        var index = $(this).index();
        console.log(index)
        number = index;
        $('.nav .content_join_price ul li').hide();
        // $('.nav .content ul li:eq(' + number + ')').show();
        if (index == 0) {
            $('.join3').css('display','block');
        }else if (index == 1) {
            $('.join4').css('display','block');
        }else if (index == 2) {
            $('.join5').css('display','block');
        }
    });
})
</script>
@endsection






































