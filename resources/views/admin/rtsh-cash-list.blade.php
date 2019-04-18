@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">融通四海</a> &raquo; 提现列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
            <form action="" method="post">
                <table class="search_tab">
                    <tr>
                        <th width="120">提现用户搜索:</th>
                        <td><input class="md" type="text" name="keywords" placeholder="请输入提现的用户姓名"></td>
                        <td><a href="javascript:;" onclick="searchInfo()" class="submit">提交</a></td>
                    </tr>
                </table>
            </form>
        </div>
    <!--结果页快捷搜索框 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">

                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content tab_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">操作人姓名</th>
                        <th class="tc">联系电话</th>
                        <th class="tc">提现银行</th>
                        <th class="tc">支行信息</th>
                        <th class="tc">提现卡号</th>
                        <th class="tc">银行卡姓名</th>
                        <th class="tc">提现金额</th>
                        <th class="tc">提现类型</th>
                        <th class="tc">提现时间</th>
                        <th class="tc">当前状态</th>
                        <th class="tc">变动时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($data->isNotEmpty())
                        @foreach ($data as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->user_name }}</td>
                            <td class="tc">{{ $value->user_phone }}</td>
                            <td class="tc">{{ $value->bank_name }}</td>
                            <td class="tc">{{ $value->bank_location }}</td>
                            <td class="tc">{{ $value->bank_card }}</td>
                            <td class="tc">{{ $value->name }}</td>
                            <td class="tc">{{ $value->price }} 元</td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(1)
                                        用户债权提现
                                        @break
                                    @case(2)
                                        用户产权提现
                                        @break
                                    @case(3)
                                        加盟商收益提现
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
                            <td class="tc">{{ $value->created_at }}</td>
                            <td class="tc">
                                @switch($value->status)
                                    @case(0)
                                        暂未提交
                                        @break
                                    @case(1)
                                        正在处理
                                        @break
                                    @case(2)
                                        已经打款
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">{{ $value->updated_at }}</td>
                            <td class="tc">
                                @switch($value->status)
                                    @case(0)
                                        <a href="javascript:;" onclick="status({{ $value->id }})">
                                            <i class="fa fa-check"></i> 提交
                                        </a>
                                        <a href="javascript:;" onclick="retract({{ $value->id }})">
                                            <i class="fa fa-mail-forward"></i> 撤回
                                        </a>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </table>

                <div class="page_list">
                    {!! $data->links() !!}
                </div>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
<script type="text/javascript">
function status(id) {
    loading();
    $.post('{{ url('admin/rtsh/cash/status/') }}/'+id+'', {
        "_token" : '{{ csrf_token() }}'
    }, function(ret) {
        layer.close(loadingBox);
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.msg(obj.msg);
            location.reload();
        }else{
            layer.msg(obj.msg, function(){
                // location.reload();
            });
        }
    });
}

function retract(id) {
    layer.confirm('你确定要撤销用户提现吗?提现款将原路返回用户账户!', {
        btn: ['取消','确定'] //按钮
    }, function(){
        layer.msg('取消成功!');
    }, function(){
        loading();
        $.post('{{ url('admin/join/cash/retract') }}', {
            "_token" : '{{ csrf_token() }}',
            "id" : id
        }, function(ret) {
            layer.close(loadingBox);
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                location.reload();
            }else{
                layer.msg(obj.msg, function(){
                    location.reload();
                });
            }
        });
    });
}

function searchInfo() {
    var text = $("input[name=keywords]").val();
    if (text == '') {
        layer.msg('查询的内容不能为空!', function(){
            // location.reload();
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/search/cash') }}/'+text, {
            "_token" : '{{ csrf_token() }}'
        }, function(ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                console.log(obj.data);
                var html = '';
                var type = '';
                var status = '';
                var btn = '';
                $.each(obj.data, function(index, val) {
                    // console.log(val.type)
                    switch(val.type)
                    {
                        case '1':
                            type = '用户债权提现';
                          break;
                        case '2':
                            type = '用户产权提现';
                          break;
                        case '3':
                            type = '加盟商收益提现';
                          break;
                        case '4':
                            type = '加盟商债权提现';
                          break;
                        case '5':
                            type = '加盟商产权提现';
                          break;
                        case '6':
                            type = '春蚕提现';
                          break;
                        default:
                            type = '未知渠道';
                    }

                    switch(val.status)
                    {
                        case '0':
                            status = '暂未提交';
                            btn = '<td class="tc"><a href="javascript:;" onclick="status('+val.id+')"><i class="fa fa-check"></i> 提交</a><a href="javascript:;" onclick="retract('+val.id+')"><i class="fa fa-mail-forward"></i> 撤回</a></td>';
                          break;
                        case '1':
                            status = '正在处理';
                            btn = '<td class="tc"><a href="javascript:;"><i class="fa fa-check"></i>无法操作</a>';
                          break;
                        case '2':
                            status = '已经打款';
                            btn = '<td class="tc"><a href="javascript:;"><i class="fa fa-check"></i>无法操作</a>';
                          break;
                        default:
                            status = '未知状态';
                            btn = '<td class="tc"><a href="javascript:;"><i class="fa fa-check"></i>无法操作</a>';
                    }

                    html += '<tr><td class="tc">'+val.id+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+val.bank_name+'</td><td class="tc">'+val.bank_location+'</td><td class="tc">'+val.bank_card+'</td><td class="tc">'+val.name+'</td><td class="tc">'+val.price+' 元</td><td class="tc">'+type+'</td><td class="tc">'+val.created_at+'</td><td class="tc">'+status+'</td><td class="tc">'+val.updated_at+'</td>'+btn+'</tr>'
                });
                // console.log(html);

                $('.tab_content').html('<table class="list_tab"><th class="tc">ID序</th><th class="tc">操作人姓名</th><th class="tc">联系电话</th><th class="tc">提现银行</th><th class="tc">支行信息</th><th class="tc">提现卡号</th><th class="tc">银行卡姓名</th><th class="tc">提现金额</th><th class="tc">提现类型</th><th class="tc">提现时间</th><th class="tc">当前状态</th><th class="tc">变动时间</th><th class="tc">操作</th>'+html+'</table>');
            }else{
                layer.msg(obj.msg, function(){
                    // location.reload();
                });
            }
        });
    }
}
</script>
@endsection