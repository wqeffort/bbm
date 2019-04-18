@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">加盟商管理</a> &raquo; 加盟商列表
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">选择分类:</th>
                    <td>
                        <select>
                            <option value="">全部</option>
                            <option value="">类别1</option>
                            <option value="">类别2</option>
                        </select>
                    </td>
                    <th width="70">关键字:</th>
                    <td><input type="text" name="keywords" placeholder="关键字"></td>
                    <td><input type="submit" name="sub" value="查询"></td>
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
                    <a href="{{ url('admin/join/add') }}"><i class="fa fa-plus"></i>添加加盟商</a>
                    <a href="{{ url('admin/join/recharge') }}"><i class="fa fa-plus"></i>加盟商充值</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序</th>
                        <th class="tc">加盟商姓名</th>
                        <th class="tc">联系电话</th>
                        <th class="tc">提现银行</th>
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
                            <td class="tc"><img src="{{ $value->bank_logo }}" alt=""></td>
                            <td class="tc">{{ $value->bank_card }}</td>
                            <td class="tc">{{ $value->name }}</td>
                            <td class="tc">{{ $value->price }} 元</td>
                            <td class="tc">
                                @switch($value->type)
                                    @case(1)
                                        用户债权提现
                                        @break
                                    @case(2)
                                        用户债权提现
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
                                    @case(7)
                                        个人推广收益
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
                                            <i class="fa fa-check"></i>提交
                                        </a>
                                        <a href="javascript:;" onclick="retract({{ $value->id }})">
                                            <i class="fa fa-mail-forward"></i>撤回
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
    $.post('{{ url('admin/join/cash/status/') }}/'+id+'', {
        "_token" : '{{ csrf_token() }}'
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
</script>
@endsection