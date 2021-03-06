@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">报表导出</a> &raquo; 春蚕提现
    </div>
    <!--面包屑导航 结束-->

	<!--结果页快捷搜索框 开始-->
	<div class="search_wrap">
            <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">选择日期:</th>
                    <td><input type="date" name="time" placeholder="选择日期"></td>
                    <td><a href="javascript:;" class="submit" onclick="searchInfo()">查 询</a></td>
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
                    <a href="javascript:;" onclick="printExcel()"><i class="fa fa-plus"></i>下载当前表格</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content" id="spring_cash">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID序号</th>
                        <th class="tc">提现用户</th>
                        <th class="tc">用户编码</th>
                        <th class="tc">用户电话</th>
                        <th class="tc">原有金额</th>
                        <th class="tc">提现金额</th>
                        <th class="tc">剩余金额</th>
                        <th class="tc">提现时间</th>
                    </tr>
                    @if ($data)
                        @foreach ($data as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc">{{ $value->user_name }}</td>
                            <td class="tc">{{ $value->uuid }}</td>
                            <td class="tc">{{ $value->user_phone }}</td>
                            <td class="tc">{{ $value->point }}</td>
                            <td class="tc">{{ $value->point - $value->new_point }}</td>
                            <td class="tc">{{ $value->new_point }}</td>
                            <td class="tc">{{ $value->created_at }}</td>
                        </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->

<script>
function searchInfo() {
    var time = $("input[name=time]").val();
    if (time == '') {
        layer.msg('查询的时间不能为空!', function(){
            // location.reload();
        });
    }else{
        loading();
        $.post('{{ url('admin/table/spring/cash/find') }}', {
            "_token" : '{{ csrf_token() }}',
            "time" : time
        }, function(ret) {
            console.log(ret);
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                // console.log(obj.data);
                var html = '';
                $.each(obj.data, function(index, val) {
                    var balance = parseInt(val.point) - parseInt(val.new_point);
                    html += '<tr><td class="tc">'+val.id+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.uuid+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+val.point+'</td><td class="tc">'+balance+'</td><td class="tc">'+val.new_point+'</td><td class="tc">'+val.created_at+'</td></tr>'
                });
                var str = '<table class="list_tab"><tr>\
                        <th class="tc">ID序号</th>\
                        <th class="tc">提现用户</th>\
                        <th class="tc">用户编码</th>\
                        <th class="tc">用户电话</th>\
                        <th class="tc">原有金额</th>\
                        <th class="tc">提现金额</th>\
                        <th class="tc">剩余金额</th>\
                        <th class="tc">提现时间</th>\
                    </tr>'+html+'</table>'
                console.log(str)
                $("#spring_cash").html(str);
            }else{
                layer.msg(obj.msg, function(){
                    // location.reload();
                });
            }
        });
    }
}
function p(s) {
    return s < 10 ? '0' + s: s;
}

function printExcel() { 
    var myDate = new Date();
    //获取当前年
    var year=myDate.getFullYear();
    //获取当前月
    var month=myDate.getMonth()+1;
    //获取当前日
    var date=myDate.getDate(); 

    var time = $("input[name=time]").val();
    if (time == '') {
        time = year+"-"+p(month)+"-"+p(date)
    }
    console.log(time);
    location.href='{{url('admin/table/spring/cash/print')}}/'+time;
}
</script>
@endsection