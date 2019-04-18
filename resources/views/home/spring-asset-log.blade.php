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
        background: -webkit-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
    background: -ms-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
    background: -o-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
    background: -moz-linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
    background: linear-gradient(140deg,#e0d600 0%,#7db802 50%,#3da503 100%);
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
        border-left: 4px solid #3da503;
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
    <h3>春蚕账户明细</h3>
    <span></span>
</div>
<div class="join_cell">
    <div class="nav">
        <div class="content">
                    <div class="list" style="    padding: 1rem;">
                        @if ($spring->isNotEmpty())
                            @foreach ($spring as $value)
                                <dl>
                                    <dd>
                                        <p>
                                            @switch($value->type)
                                                @case(1)
                                                    创业基金(现金)
                                                    @break
                                                @case(2)
                                                    创业基金(积分)
                                                    @break
                                                @case(3)
                                                    创业基金(兑换)
                                                    @break
                                                @case(4)
                                                    兑换失败,返还账户
                                                    @break
                                                @case(5)
                                                    旧系统提现扣除(已打款)
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
                            @endforeach
                        @endif
                    </div>
        </div>
    </div>
</div>

<script type="text/javascript">
</script>
@endsection






































