@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">用户管理</a> &raquo; 信息统计
    </div>
    <!--面包屑导航 结束-->
    <div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">开始时间:</th>
                    <td><input type="date" name="start" placeholder="开始时间"></td>
                    <th width="120">结束时间:</th>
                    <td><input type="date" name="end" placeholder="结束时间"></td>
                    <td>
                        <a href="javascript:;" class="submit" onclick="sub()">查 询</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div class="result_wrap">
        <div class="result_title">
            <h3>实时用户统计</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>用户总数</label><span id="userAll">{{ $userAll }} 人</span>
                    <label>付费用户</label><span id="userPay">{{ $userPay }} 人</span>
                    <label>非付费用户</label><span id="userFree">{{ $userFree }} 人</span>
                </li>
                <li>
                    <label>体验会员</label><span id="rank1">{{ $rank1 }} 人</span>
                    <label>男爵会员</label><span id="rank2">{{ $rank2 }} 人</span>
                    <label>子爵会员</label><span id="rank3">{{ $rank3 }} 人</span>
                    <label>伯爵会员</label><span id="rank4">{{ $rank4 }} 人</span>
                    <label>侯爵会员</label><span id="rank5">{{ $rank5 }} 人</span>
                    <label>公爵会员</label><span id="rank6">{{ $rank6 }} 人</span>
                </li>
                <li>
                    <label>加盟商人数</label><span id="userJoin">{{ $userJoin }} 人</span>
                    <label>合伙人人数</label><span id="userJoin">{{ $userSale }} 人</span>
                    <label>春蚕人数</label><span id="userSpring">{{ $userSpring }} 人</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="result_wrap">
        <div class="result_content">
            <table class="list_tab" id="goodsList">

            </table>
        </div>
    </div>
    {{-- 展示充值情况 默认为当前.进入页面后发送ajax进行获取 --}}
    <div class="result_wrap">
        <div class="result_title">
            <h3>上月充值统计</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>上月总金额</label><span id="joinOrderAll">{{ $joinOrder->count() }} 人  (共: {{ $joinOrder->sum('point') }})</span>
                <label>会籍购买</label><span id="joinOrderPay">{{ $joinOrderPay->count() }} 人  (共: {{$joinOrderPay->sum('point')}} 积分)</span>
                    <label>积分充值</label><span id="joinOrderRecharge">{{ $joinOrderRecharge->count() }} 人  (共: {{ $joinOrderRecharge->sum('point') }} 积分)</span>
                </li>
            </ul>
            <table class="list_tab"  id="info">
                <tr>
                    <th>ID序</th>
                    <th>订单编号</th>
                    <th>用户姓名</th>
                    <th>涉及积分</th>
                    <th>操作类型</th>
                    <th>加盟商</th>
                    <th>操作时间</th>
                </tr>
                @foreach ($joinOrder as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->num }}</td>
                        <td>{{ $item->user_name }}</td>
                        <td>{{ $item->point }}</td>
                        <td>@if ($item->type == 1)
                            购买会籍
                            @else
                            积分充值
                        @endif</td>
                        <td>{{ $item->join_name }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <!--结果集列表组件 结束-->
    <script>
        // 查询数据
        function sub() {
            var start = $('input[name=start]').val();
            var end = $('input[name=end]').val();
            console.log(start)
            console.log(end)
            if (start == '' || end == '') {
                layer.msg('开始时间和结束时间不能为空!',function () {
                });
            }else{
                if (start > end) {
                    layer.msg('开始时间不能大于结束时间!',function () {
                });
                }else{
                    loading();
                    $.post('{{ url('admin/user/info') }}',{
                        "_token" : '{{ csrf_token() }}',
                        "start" : start,
                        "end" : end
                    },function (ret) {
                        var obj = $.parseJSON(ret);
                            layer.close(loadingBox);
                            if (obj.status == 'success') {
                                console.log(obj.data)
                                $('#userAll').text(obj.data.userAll+' 人');
                                $('#userPay').text(obj.data.userPay+' 人');
                                $('#userFree').text(obj.data.userFree+' 人');
                                $('#rank1').text(obj.data.rank1+' 人');
                                $('#rank2').text(obj.data.rank2+' 人');
                                $('#rank3').text(obj.data.rank3+' 人');
                                $('#rank4').text(obj.data.rank4+' 人');
                                $('#rank5').text(obj.data.rank5+' 人');
                                $('#rank6').text(obj.data.rank6+' 人');
                                $('#userJoin').text(obj.data.userJoin+' 人');
                                $('#userSpring').text(obj.data.userSpring+' 人');
                                var html = '<tr>\
                        <th class="tc">ID序</th>\
                        <th class="tc">头像</th>\
                        <th class="tc">昵称</th>\
                        <th class="tc">姓名</th>\
                        <th class="tc">电话</th>\
                        <th class="tc">等级</th>\
                        <th class="tc">变动时间</th>\
                        <th class="tc">注册时间</th>\
                        <th class="tc">查看信息</th>\
                    </tr>';
                                $.each(obj.data.rank, function(index, val) {
                                    html += '<tr><td class="tc">'+index+'</td><td class="tc"><img style="width:3rem;border-radius: none;" src="'+val.user_pic+'"></td><td class="tc">'+val.user_nickname+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+val.rank+'</td><td class="tc">'+val.time+'</td><td class="tc">'+val.created_at+'</td><td class="tc"><a href="{{ url('admin/view/user') }}/'+val.user_uuid+'"><i class="fa fa-search"></i>查看资料</a> | <a href="{{ url('admin/view/log') }}/'+val.user_uuid+'"><i class="fa fa-file-text"></i>账户明细</a></td></tr>'
                                });
                                $('#goodsList').html(html);
                            }else{
                                layer.msg(obj.msg,function () {
                                    location.reload();
                                });
                            }
                    });
                }
            }
        }
    </script>
@endsection