@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">城市合伙人</a> &raquo; 信息统计
    </div>
    <!--面包屑导航 结束-->
    {{-- <div class="search_wrap">
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
    </div> --}}
    @foreach ($data as $value)
    <div class="result_wrap">
        <div class="result_title">
            <h3>哈尔滨城市合伙人 信息统计({{date('m',strtotime("last month"))}} 月份)</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>区域加盟商总数</label><span id="rank1">{{ $value['join']->count() }} 人</span>
                    <label>区域业绩总额</label><span id="rank2">{{ $value['total'] }} 积分</span>
                    <label>合伙人收益额</label><span id="rank3">{{ $value['total'] * 0.05 }} 积分</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="result_wrap">
        <div class="result_content">
            <table class="list_tab" id="goodsList">
                <tr>
                    <th>ID序</th>
                    <th>订单编号</th>
                    <th>用户姓名</th>
                    <th>涉及积分</th>
                    <th>操作类型</th>
                    <th>加盟商</th>
                    <th>操作时间</th>
                </tr>
                @foreach ($value['history'] as $item)
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
    @endforeach
    {{-- 展示充值情况 默认为当前.进入页面后发送ajax进行获取 --}}
    
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