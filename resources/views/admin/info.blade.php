@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">商品管理</a> &raquo; 添加商品
    </div>
    <!--面包屑导航 结束-->

    <div class="result_wrap">
        <div class="result_title">
            <h3>实时用户统计</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>注册用户</label><span>{{ $userAll->count() }}</span>
                    <label>会员用户</label><span>{{ $memberAll }}</span>
                    <label>加盟商数量</label><span>{{ $joinAll }}</span>
                    <label>春蚕数量</label><span>{{ $springAll }}</span>
                </li>
                <li>
                    <label>男爵会员</label><span>{{ $rank_2 }}</span>
                    <label>子爵会员</label><span>{{ $rank_3 }}</span>
                    <label>伯爵会员</label><span>{{ $rank_4 }}</span>
                    <label>侯爵会员</label><span>{{ $rank_5 }}</span>

                </li>
                <li>
                    <label>用户基本积分</label><span>{{ $userAll->sum('user_point') }}</span>
                    <label>用户赠送积分</label><span>{{ $userAll->sum('user_point_give') }}</span>
                </li>
            </ul>
        </div>
        {{-- <div class="result_title">
            <h3>实时商城统计</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>总订单数</label><span></span>
                    <label>总交易额</label><span></span>
                    <label>微信支付</label><span></span>
                    <label>积分支付</label><span></span>
                </li>
            </ul>
        </div> --}}
    </div>
    <div class="result_wrap">
        <div class="result_title">
            <h3>系统基本信息</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>操作系统</label><span>{{ php_uname('s')}} centOS7.5  </span>
                </li>
                <li>
                    <label>运行环境</label><span>php{{ PHP_VERSION }} {{ $_SERVER['SERVER_SOFTWARE'] }}</span>
                </li>
                <li>
                    <label>Github-版本</label><span><a> appyjj/jaclub v-1.0</a></span>
                </li>
                <li>
                    <label>上传附件限制</label><span>{{ ini_get('upload_max_filesize') }}</span>
                </li>
                <li>
                    <label>北京时间</label><span>{{ date("Y年m月d日 H时i分m秒",time()) }}</span>
                </li>
            </ul>
        </div>
    </div>
    <!--结果集列表组件 结束-->
@endsection