@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">融通四海</a> &raquo; 信息管理
    </div>
    <!--面包屑导航 结束-->

	<!--结果集标题与导航组件 开始-->
	<div class="result_wrap">
        <div class="result_title">
            <h3>快捷操作</h3>
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="#"><i class="fa fa-plus"></i>按钮</a>
                <a href="#"><i class="fa fa-recycle"></i>按钮</a>
                <a href="#"><i class="fa fa-refresh"></i>按钮</a>
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->
<style>
.edit {
    background: #C40000;
    color: #FFF !important;
    padding: .3rem .5rem;
    border-radius: .2rem;
}
</style>
    <div class="result_wrap" @if (session('admin')->cate == 5)
        style="display: none;"
    @endif>
        <table class="add_tab">
            <tr>
                <th width="120">用户头像</th>
                <td>
                    <img class="user_pic" src="{{ $user->user_pic }}" alt="">
                </td>
            </tr>
            <tr>
                <th width="120">用户昵称</th>
                <td>
                    {{ $user->user_nickname }}
                </td>
            </tr>
            <tr>
                <th width="120">用户姓名</th>
                <td>
                    <input type="text" name="name" value="{{ $user->user_name }}" disabled="disabled">
                </td>
            </tr>
            <tr>
                <th width="120">用户手机</th>
                <td>
                    <input type="text" name="phone" value="{{ $user->user_phone }}" disabled="disabled">
                </td>
            </tr>
            <tr>
                <th width="120">身份证编号</th>
                <td>
                    <a href="javascript:;" onclick="viewUid()">{{ $user->user_uid }}</a>
                        <img id="tong" style="display: none;width: 100%;" src="{{ url($user->user_uid_a) }}" alt="身份证">
                    {{-- <a href="{{ url($user->user_uid_a) }}" target="_blank">{{ $user->user_uid }}</a> --}}
                </td>
            </tr>
            <tr>
                <th width="120">用户等级</th>
                <td>
                    @switch($user->user_rank)
                        @case(0)
                            普通用户
                            @break
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
                        @case(10)
                            内部员工
                            @break
                        @default
                            未知级别
                    @endswitch
                </td>
            </tr>
            <tr>
                <th style="background: #3e7ff5;color: #FFF;" width="120">债权账户</th>
                <td>
                    余额: <input type="text" name="rtsh_bond" value="{{ $user->rtsh_bond }}" disabled="disabled"><br>
                    <input style="display: none;" type="radio" value="rtsh_bond_up" name="new_rtsh_bond" id="rtsh_bond_up">
                    <label style="padding: .2rem .5rem;color: #000;font-weight: bold;" for="rtsh_bond_up" onclick="again_rtsh_bond()">增加</label>
                    <input style="display: none;" type="radio" value="rtsh_bond_down" name="new_rtsh_bond" id="rtsh_bond_down" checked="checked">
                    <label style="padding: .2rem .5rem;color: #000;font-weight: bold;" for="rtsh_bond_down" onclick="again_rtsh_bond()">减少</label><br>
                    差值: <input type="number" id="rtsh_bond_val" name="rtsh_bond_val" value="0">
                    <p>修改后的余额: <b id="new_rtsh_bond" style="color:#C40000;">{{ $user->rtsh_bond }}</b></p>
                    备注信息:
                    <textarea id="rtsh_bond_log" cols="30" rows="10"></textarea>
                    <a href="javascript:;" onclick="edit('rtsh_bond')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>

            <tr>
                <th style="background: #5cad04;color: #FFF;" width="120">冻结账户</th>
                <td>
                    余额: <input type="text" name="rtsh_frozen" value="{{ $user->rtsh_frozen }}" disabled="disabled"><br>
                    <input style="display: none;" type="radio" value="rtsh_frozen_up" name="new_rtsh_frozen" id="rtsh_frozen_up">
                    <label style="padding: .2rem .5rem;color: #000;font-weight: bold;" for="rtsh_frozen_up" onclick="again_rtsh_frozen()">增加</label>
                    <input style="display: none;" type="radio" value="rtsh_frozen_down" name="new_rtsh_frozen" id="rtsh_frozen_down" checked="checked">
                    <label style="padding: .2rem .5rem;color: #000;font-weight: bold;" for="rtsh_frozen_down" onclick="again_rtsh_frozen()">减少</label><br>
                    差值: <input type="number" id="rtsh_frozen_val" name="rtsh_frozen_val" value="0">
                    <p>修改后的余额: <b id="new_rtsh_frozen" style="color:#C40000;">{{ $user->rtsh_frozen }}</b></p>
                    备注信息:
                    <textarea id="rtsh_frozen_log" cols="30" rows="10"></textarea>
                    <a href="javascript:;" onclick="edit('rtsh_frozen')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>
            <tr>
                <th>账户日志:</th>
                <td>
                    <script id="editor" name="goods_desc" type="text/plain" style="width:800px;height:500px;">
                        {!! $user->rtsh_desc !!}
                    </script>
                    <script type="text/javascript">
                        var ue = UE.getEditor('editor');
                    </script>
                    <br>
                    <p><a href="javascript:;" onclick="edit('rtsh_desc')" class="edit"><i class="fa fa-pencil"></i>保存日志</a></p>
                </td>
            </tr>
        </table>
    </div>
    <div class="result_wrap">
        <div class="result_title">
            <h3>账户流水记录</h3>
        </div>
    </div>
    <div class="result_wrap">
        <div class="result_content">
            <table class="list_tab">
                <tr>
                    <th class="tc">ID序</th>
                    <th class="tc">项目期号</th>
                    <th class="tc">订单编号</th>
                    <th class="tc">债权账户</th>
                    <th class="tc">债权账户(变动后)</th>
                    <th class="tc">冻结账户</th>
                    <th class="tc">冻结账户(变动后)</th>
                    <th class="tc">变动原因</th>
                    <th class="tc">变动时间</th>
                    <th class="tc">操作人员</th>
                    <th class="tc">描述信息</th>
                </tr>
                @foreach ($log as $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ substr($value->num, 0,4) }}年{{ substr($value->num, 4,2) - 1 }}月</td>
                        <td>{{ $value->num }}</td>
                        <td>{{ $value->price }}</td>
                        <td>{{ $value->new_price }}<b style="
                        @if (!$value->price && !$value->new_price)
                            display: none;
                        @else
                            @if ($value->new_price > $value->price)
                                color:green;
                            @else
                                color:#C40000;
                            @endif
                        @endif
                        ">({{ $value->new_price - $value->price }})</b></td>
                        <td>{{ $value->frozen }}</td>
                        <td>{{ $value->new_frozen }}<b style="
                        @if (!$value->frozen && !$value->new_frozen)
                            display: none;
                        @else
                            @if ($value->new_frozen > $value->frozen)
                                color:green;
                            @else
                                color:#C40000;
                            @endif
                        @endif
                        ">({{ $value->new_frozen - $value->frozen }})</b></td>
                        <td>@if ($value->end == 1)
                            撤销提现
                            @else
                        @switch($value->type)
                            @case(1)
                                产权购买
                                @break
                            @case(2)
                                债权购买
                                @break
                            @case(3)
                                用户提现
                                @break
                            @case(3)
                                债权续期
                                @break
                            @case(5)
                                到期返款
                                @break
                            @case(6)
                                债权账户调动
                                @break
                            @case(7)
                                债权提成账户调动
                                @break
                            @case(8)
                                后台账户调整
                                @break
                            @case(9)
                                后台订单调整
                                @break
                            @case(10)
                                提现撤销
                                @break
                        @endswitch
                    @endif
                        </td>
                        <td>{{ $value->created_at }}</td>
                        <td>{{ $value->admin }}</td>
                        <td>{{ $value->desc }}</td>
                    </tr>
                @endforeach
            </table>

            <div class="page_list">
                {!! $log->links() !!}
            </div>
        </div>
    </div>
<script>
function viewUid() {
    layer.open({
      type: 1,
      title: false,
      closeBtn: 0,
      skin: 'layui-layer-nobg', //没有背景色
      shadeClose: true,
      content: $('#tong')
    });
}

function edit(i) {
    var type = '';
    var val = '';
    var log = '';
    switch (i) {
        case 'rtsh_desc' :
            type = 'rtsh_desc';
            val = UE.getEditor('editor').getContent();
            break;
        case 'rtsh_bond' :
            type = 'rtsh_bond';
            val = $('#new_rtsh_bond').text();
            log = $('#rtsh_bond_log').val()
            break;
        case 'rtsh_frozen' :
            type = 'rtsh_frozen';
            val = $('#new_rtsh_frozen').text();
            log = $('#rtsh_frozen_log').val()
            break;
    }
    if (type != '' && val != '') {
        loading();
        $.post('{{ url('admin/rtsh/user') }}/{{ $user->user_uuid }}', {
            "_token" : '{{ csrf_token() }}',
            "type" : type,
            "val" : val,
            "log" : log
        }, function(ret) {
            layer.close(loadingBox);
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                setTimeout(function(){
                    location.reload()
                }, 1500);
            }else{
                layer.msg(obj.msg, function(){
                //关闭后的操作
                });
            }
        });
    }else{
        layer.msg('修改项的值不能为空!', function(){
        //关闭后的操作
        });
    }
}

$("#rtsh_bond_val").bind("input propertychange",function(){
    var type = $("input[name='new_rtsh_bond']:checked").val();
    var rtsh_bond = $('input[name=rtsh_bond]').val();
    if (type == 'rtsh_bond_up') {
        var new_rtsh_bond = parseInt(rtsh_bond) + parseInt($(this).val());
    }else{
         var new_rtsh_bond = parseInt(rtsh_bond) - parseInt($(this).val());
    }
    $('#new_rtsh_bond').html(new_rtsh_bond);
});

function again_rtsh_bond() {
    $('input[name=rtsh_bond_val]').val('0');
    var rtsh_bond = $('input[name=rtsh_bond]').val();
    $('#new_rtsh_bond').html(rtsh_bond);
}

$("#rtsh_frozen_val").bind("input propertychange",function(){
    var type = $("input[name='new_rtsh_frozen']:checked").val();
    var rtsh_frozen = $('input[name=rtsh_frozen]').val();
    if (type == 'rtsh_frozen_up') {
        var new_rtsh_frozen = parseInt(rtsh_frozen) + parseInt($(this).val());
    }else{
         var new_rtsh_frozen = parseInt(rtsh_frozen) - parseInt($(this).val());
    }
    $('#new_rtsh_frozen').html(new_rtsh_frozen);
});

function again_rtsh_frozen() {
    $('input[name=rtsh_frozen_val]').val('0');
    var rtsh_frozen = $('input[name=rtsh_frozen]').val();
    $('#new_rtsh_frozen').html(rtsh_frozen);
}
</script>
@endsection