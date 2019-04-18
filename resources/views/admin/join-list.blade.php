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
                    <th width="70">搜索用户:</th>
                    <td><input type="text" name="keywords" placeholder="姓名或者电话"></td>
                    <td><a class="submit" href="javascript:;" onclick="searchAll()">搜 索</a>
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
                        <th class="tc">头像</th>
                        <th class="tc">姓名</th>
                        <th class="tc">积分余额</th>
                        <th class="tc">当前等级</th>
                        <th class="tc">上次变动时间</th>
                        <th class="tc">状态</th>
                        <th class="tc">礼品赠送</th>
                        <th class="tc">协议类型</th>
                        <th class="tc">加盟时间</th>
                        <th class="tc">操作</th>
                    </tr>
                    @if ($join->isNotEmpty())
                        @foreach ($join as $value)
                        <tr>
                            <td class="tc">{{ $value->id }}</td>
                            <td class="tc"><img class="user_pic" src="{{ asset($value->user_pic) }}">
                            <td class="tc">{{ $value->user_name }}</td>
                            <td class="tc">{{ $value->point }}</td>
                            <td class="tc">
                                @switch($value->user_rank)
                                    @case(1)
                                        体验会员
                                        @break
                                    @case(2)
                                        男爵会员
                                        @break
                                    @case(3)
                                        子爵会员
                                        @break
                                    @case(4)
                                        伯爵会员
                                        @break
                                    @case(5)
                                        侯爵会员
                                        @break
                                    @case(6)
                                        公爵会员
                                        @break
                                    @case(9)
                                        内部会员
                                        @break
                                    @default
                                        非付费用户
                                        @break
                                @endswitch
                            </td>
                            <td class="tc">{{ $value->updated_at }}</td>
                            <td class="tc">
                                @if ($value->status == 1)
                                    <a href="javascript:;" onclick="status({{ $value->id }})">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" onclick="status({{ $value->id }})" style="color:#000">
                                        <i class="fa fa-close"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="tc">
                                @if ($value->is_give == 1)
                                    <a href="javascript:;">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @else
                                    <a href="javascript:;" style="color:#000">
                                        <i class="fa fa-close"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="tc">
                                @if ($value->type == 1)
                                    <span style="color: #C40000">合伙人</span>
                                @else
                                @switch($value->protocol)
                                    @case(0)
                                        <select id="select" onchange="addProtocol({{ $value->id }})">
                                            <option value="0">选择协议</option>
                                            <option value="1">普通协议</option>
                                            <option value="2">春蚕协议</option>
                                        </select>
                                        {{-- <span onclick="addProtocol({{ $value->id }})" style="color: #C40000">添加协议</span> --}}
                                        @break
                                    @case(1)
                                        <span style="color: blue">普通协议</span>
                                        @break
                                    @case(2)
                                        <span style="color: green">春蚕协议</span>
                                        @break
                                @endswitch
                                @endif
                            </td>
                            <td class="tc">{{ $value->created_at }}</td>
                            <td class="tc">
                                
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </table>

                <div class="page_list">
                    {!! $join->links() !!}
                </div>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
<script type="text/javascript">
function status(id) {
    loading();
    $.post('{{ url('admin/join/status') }}/'+id+'', {
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

function addProtocol(id) {
    var protocol = $("#select option:selected").val();
    var text = $("#select option:selected").text();
    //询问框
    if (protocol == 1) {
        layer.confirm('你确定要添加加盟商协议为 "'+text+'"吗?操作确定后无法取消!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            loading();
            $.post('{{ url('admin/join/protocol') }}',{
                '_token' : '{{ csrf_token() }}',
                'protocol' : protocol,
                'id' : id
            },function (ret) {
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
        }, function(){
            layer.msg('已经取消');
            location.reload();
        });
    }else{
        layer.confirm('你确定要添加加盟商协议为 "'+text+'"吗?操作确定后无法取消!', {
            btn: ['确定','取消'] //按钮
        }, function(){
            // 弹出资料补充层 补充春蚕资料
            //页面层-自定义
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                area: ['500px', '300px'],
                shadeClose: true,
                skin: 'yourclass',
                content: '<div><ul style="padding: 2rem;text-align:center;"><h2>加盟日期设定</h2><li style="display: flex;padding:.5rem 0;justify-content: space-between;"><span>请选择开始日期</span><input type="date" id="time"onchange="handleValue(this.value)"/></li><li style="display: flex;padding:.5rem 0;justify-content: space-between;"><span>结束日期:</span><b id="endTime"></b></li><li style="display: flex;padding:.5rem 0;justify-content: space-between;"><span>补偿积分:</span><b id=addDay></b><b id="addPoint"></b> 积分</li><li style="display: flex;padding:.5rem 0;justify-content: space-between;"><span>补偿创业基金:</span><b id=addDay1></b><b id="addPrice"></b> 创业基金</li></ul><p style="text-align:center;"><span style="padding: 0.5rem 2rem;background: #C40000;color: #FFF;" onclick="sub('+id+')">确 认</span></p></div>'
            });
        }, function(){
            layer.msg('已经取消');
            location.reload();
        });
    }
}

// 监听输入框
function handleValue(time) {
    var day = new Date(time);//这里日期是传递过来的，可以自己改
    day.setDate(day.getDate() + 270);//天数+270
    var newDay = day.getFullYear() + "-" + (day.getMonth() + 1) + "-" + day.getDate();//新日期
    // alert(newDay); // 结束日期
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    if (month < 10) {
        month = "0" + month;
    }
    if (day < 10) {
        day = "0" + day;
    }
    var nowDate = year + "-" + month + "-" + day;

    var d1 = new Date(time);
    var d2 = new Date(nowDate);
    // console.log(parseInt(d2 - d1) / 1000 / 60 / 60 /24);
    var addPoint = (parseInt(d2 - d1) / 1000 / 60 / 60 / 24) * 500 + 500;
    var addDay = '共  '+(parseInt(d2 - d1) / 1000 / 60 / 60 / 24 + 1)+ '  天';
    if (addPoint > 5000) {
        var addPrice = parseInt(addPoint) - 5000;
        var addDay1 = '共  '+((parseInt(d2 - d1) / 1000 / 60 / 60 / 24) - 10 + 1) +'  天';
        addPoint = 5000;
        addDay = '共  10  天';
    }else{
        var addPrice = 0;
        var addDay1 = 0;
    }
    $('#endTime').text(newDay);
    $('#addPoint').text(addPoint);
    $('#addPrice').text(addPrice);
    $('#addDay').text(addDay);
    $('#addDay1').text(addDay1);
}
function sub(id) {
    var time = $('#time').val();
    var point = $('#addPoint').text();
    var price = $('#addPrice').text();
    // 请求提交春蚕协议
    loading();
    $.post('{{ url('amdin/join/spring') }}', {
        "_token" : '{{ csrf_token() }}',
        "time" : time,
        "id" : id,
        "point" : point,
        "price" : price
    }, function (ret) {
        layer.closeAll();
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.msg(obj.msg);
            // location.reload();
        }else{
            layer.msg(obj.msg, function(){
                // location.reload();
            });
        }
    });
}
</script>
@endsection