@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">用户管理</a> &raquo; 用户信息
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
.mui-switch {
            width: 52px;
            height: 31px;
            position: relative;
            border: 1px solid #dfdfdf;
            background-color: #fdfdfd;
            box-shadow: #dfdfdf 0 0 0 0 inset;
            border-radius: 20px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            background-clip: content-box;
            display: inline-block;
            -webkit-appearance: none;
            user-select: none;
            outline: none; }
        .mui-switch:before {
            content: '';
            width: 29px;
            height: 29px;
            position: absolute;
            top: 0px;
            left: 0;
            border-radius: 20px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4); }
        .mui-switch:checked {
            border-color: #64bd63;
            box-shadow: #64bd63 0 0 0 16px inset;
            background-color: #64bd63; }
        .mui-switch:checked:before {
            left: 21px; }
        .mui-switch.mui-switch-animbg {
            transition: background-color ease 0.4s; }
        .mui-switch.mui-switch-animbg:before {
            transition: left 0.3s; }
        .mui-switch.mui-switch-animbg:checked {
            box-shadow: #dfdfdf 0 0 0 0 inset;
            background-color: #64bd63;
            transition: border-color 0.4s, background-color ease 0.4s; }
        .mui-switch.mui-switch-animbg:checked:before {
            transition: left 0.3s; }
        .mui-switch.mui-switch-anim {
            transition: border cubic-bezier(0, 0, 0, 1) 0.4s, box-shadow cubic-bezier(0, 0, 0, 1) 0.4s; }
        .mui-switch.mui-switch-anim:before {
            transition: left 0.3s; }
        .mui-switch.mui-switch-anim:checked {
            box-shadow: #64bd63 0 0 0 16px inset;
            background-color: #64bd63;
            transition: border ease 0.4s, box-shadow ease 0.4s, background-color ease 1.2s; }
        .mui-switch.mui-switch-anim:checked:before {
            transition: left 0.3s;
        }
</style>
{{-- {{ dd($data) }} --}}
    <div class="result_wrap">
        <table class="add_tab">
            <tr>
                <th width="120">用户头像</th>
                <td>
                    <img class="user_pic" src="{{ $data['user']->user_pic }}" alt="">
                </td>
            </tr>
            <tr>
                <th width="120">用户昵称</th>
                <td>
                    {{ $data['user']->user_nickname }}
                </td>
            </tr>
            <tr>
                <th width="120">用户姓名</th>
                <td>
                    <input type="text" name="name" value="{{ $data['user']->user_name }}"> <a href="javascript:;" onclick="edit('name')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>
            <tr>
                <th width="120">用户手机</th>
                <td>
                    <input type="text" name="phone" value="{{ $data['user']->user_phone }}"> <a href="javascript:;" onclick="edit('phone')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>
            <tr>
                <th width="120">身份证编号</th>
                <td>
                    <a href="javascript:;" onclick="viewUid()">{{ $data['user']->user_uid }}</a>
                        @if ($data['user']->user_uid_a)
                            <img id="tong" onclick="openBeii()" style="display: none;width: 100%;" src="{{ url($data['user']->user_uid_a) }}" alt="身份证">
                        @endif
                        @if ($data['user']->user_uid_b)
                            <img id="bei" style="display: none;width: 100%;" src="{{ url($data['user']->user_uid_b) }}" alt="身份证">
                        @endif
                    {{-- <a href="{{ url($data['user']->user_uid_a) }}" target="_blank">{{ $data['user']->user_uid }}</a> --}}
                </td>
            </tr>
            <tr>
                <th width="120">用户等级</th>
                <td>
                    <select name="rank" id="rank">
                        <option value="{{$data['user']->user_rank}}">@switch($data['user']->user_rank)
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
                    @endswitch</option>
                    <option value="0">普通用户</option>
                    <option value="1">体验会员</option>
                    <option value="2">男爵会员</option>
                    <option value="3">子爵会员</option>
                    <option value="4">伯爵会员</option>
                    <option value="5">侯爵会员</option>
                    <option value="6">公爵会员</option>
                    <option value="10">内部员工</option>
                    </select>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <th width="120">备注信息</th>
                <td><textarea id="rankLog" cols="30" rows="20"></textarea></td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <a href="javascript:;" onclick="rank('{{$data['user']->user_uuid}}')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>
            <tr style="display:none;">
                <th width="120">用户登录密码</th>
                <td>
                    <input type="text" name="userPassword" value="{{ $password }}"> <a href="javascript:;" onclick="edit('userPassword')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>
            <tr style="display:none;">
                <th width="120">用户提现密码</th>
                <td>
                    <input type="text" name="cashPassword" value="{{ $cash_password }}"> <a href="javascript:;" onclick="edit('cashPassword')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>
            <tr>
                <th width="120">基本积分</th>
                <td>
                    <input type="text" value="{{ $data['user']->user_point }}" disabled="disabled">
                </td>
            </tr>
            <tr>
                <th width="120">赠送积分</th>
                <td>
                    <input type="text" value="{{ $data['user']->user_point_give }}" disabled="disabled">
                </td>
            </tr>
            <tr>
                <th width="120">绑定的用户</th>
                <td>
                    @if ($userPid)
                        <a href="{{ url('admin/view/user') }}/{{ $userPid->user_uuid }}">{{ $userPid->user_name }}</a>
                    @else
                        未绑定上级用户
                    @endif
                </td>
            </tr>
            <tr>
                <th width="120">绑定的加盟商</th>
                <td>
                    @if ($joinPid)
                        <a href="{{ url('admin/view/user') }}/{{ $joinPid->user_uuid }}">{{ $joinPid->user_name }}</a>
                    @else
                        未绑定加盟商
                    @endif
                </td>
            </tr>
        </table>
    </div>

@if (session('admin')->rank > 4)
    <div class="result_wrap">
        <table class="add_tab">
            <tr>
                <th width="120">选择充值的类型</th>
                <td>
                    <select id="recharge" onchange="toRecharge()">
                        <option value="7">生日充值</option>
                        <option value="0">用户打款充值</option>
                        <option value="10">自定义充值</option>
                    </select>
                </td>
            </tr>
            <tr id="point">
                <th width="120">基础积分</th>
                <td>
                    <input type="number" name="recharge_point" placeholder="请输入充值的基础积分" required="required">
                </td>
            </tr>
            <tr id="point_give">
                <th width="120">赠送积分</th>
                <td>
                    <input type="number" name="recharge_point_give" placeholder="请输入充值的赠送积分" required="required">
                </td>
            </tr>
            <tr>
                <th width="120">备注信息</th>
                <td><textarea id="rechargeLog" cols="30" rows="20"></textarea></td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <a href="javascript:;" onclick="recharge()" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                </td>
            </tr>

        </table>
    </div>
@endif
{{-- {{ dd(1) }} --}}
    <div class="result_wrap">
        <table class="add_tab">
            @if ($bank->isNotEmpty())
                <tr>
                    <th width="120">用户银行卡</th>
                    <td>
                        @foreach ($bank as $value)
                            <img src="{{ $value->bank_logo }}" alt="{{ $value->bank_code }}"><br>
                            <input type="text" name="namebank" value="{{ $value->name }}"><br>
                            <input type="text" name="bank_name" value="{{ $value->bank_name }}"><br>
                            <input type="number" name="bank_card" value="{{ $value->bank_card }}"><br>
                            <input type="text" name="bank_location" value="{{ $value->bank_location }}"><br>
                            <a href="javascript:;" onclick="bank('{{ $value->id }}')" class="edit"><i class="fa fa-pencil"></i>修 改</a> <a href="javascript:;" onclick="del('{{ $value->id }}')" class="edit" style="background:green;"><i class="fa fa-pencil"></i>删 除</a>
                            <br>
                            <hr>
                        @endforeach
                    </td>
                </tr>  
            @endif
        </table>
    </div>

    <div class="result_wrap">
        <table class="add_tab">
            @if ($data['join'])
                <tr>
                    <th width="120">加盟商协议</th>
                    <td>
                        @switch($data['join']->protocol)
                            @case(0)
                                <input type="text" value="未签署协议
                        " disabled="disabled">
                                @break
                            @case(1)
                                <input type="text" value="加盟商协议
                        " disabled="disabled">
                                @break
                            @case(2)
                                <input type="text" value="春蚕协议
                        " disabled="disabled">
                                @break
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <th width="120">上级加盟商</th>
                    <td>
                        @if ($data['join']->pid)
                            <a href="{{ url('admin/view/user') }}/{{ $data['join']->pid }}">点击查看</a>
                        @else
                            未绑定加盟商
                        @endif
                    </td>
                </tr>
                <tr>
                    <th width="120">普通积分</th>
                    <td>
                        <input type="text" value="{{ $data['join']->point }}" disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <th width="120">赠送积分</th>
                    <td>
                        <input type="text" value="{{ $data['join']->point_give }}" disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <th width="120">返佣积分</th>
                    <td>
                        <input type="text" value="{{ $data['join']->point_fund }}" disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <th width="120">春蚕账户</th>
                    <td>
                        <input type="text" value="{{ $data['join']->price }}" disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <th width="120">现金账户</th>
                    <td>
                        <input type="text" value="{{ $data['join']->join_cash }}" disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <th width="120">债权提成</th>
                    <td>
                        <input type="text" value="{{ $data['join']->rtsh_bond }}" disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <th width="120">春蚕时间</th>
                    <td>
                        {{ $data['join']->spring_start }} 至 {{ $data['join']->spring_end }}
                    </td>
                </tr>
                <tr style="display:none;">
                    <th width="120">登录密码</th>
                    <td>
                        <input type="text" name="joinPassword" value="{{ $join_password }}"> <a href="javascript:;" onclick="edit('joinPassword')" class="edit"><i class="fa fa-pencil"></i>修 改</a>
                    </td>
                </tr>
                <tr>
                    <th>系统状态:</th>
                    <td>
                        @if ($data['join']->status)
                            <label onclick="updateJoinStatus({{ $data['join']->id }})"><input class="mui-switch mui-switch-anim" type="checkbox" checked>
                        @else
                            <label onclick="updateJoinStatus({{ $data['join']->id }})"><input class="mui-switch mui-switch-anim" type="checkbox">
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    <th width="120">加盟状态</th>
                    <td>
                        未开通加盟商
                    </td>
                </tr>
            @endif
        </table>
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

function openBeii() {
    layer.open({
      type: 1,
      title: false,
      closeBtn: 0,
      skin: 'layui-layer-nobg', //没有背景色
      shadeClose: true,
      content: $('#bei')
    });
}

function edit(i) {
    var type = '';
    var val = '';
    switch (i) {
        case 'joinPassword' :
            type = 'joinPassword';
            val = $('input[name=joinPassword]').val();
            break;
        case 'name' :
            type = 'name';
            val = $('input[name=name]').val();
            break;
        case 'phone' :
            type = 'phone';
            val = $('input[name=phone]').val();
            break;
        case 'userPassword' :
            type = 'userPassword';
            val = $('input[name=userPassword]').val();
            break;
        case 'cashPassword' :
            type = 'cashPassword';
            val = $('input[name=cashPassword]').val();
            break;
    }
    if (type != '' && val != '') {
        loading();
        $.post('{{ url('admin/view/user') }}/{{ $data['user']->user_uuid }}', {
            "_token" : '{{ csrf_token() }}',
            "type" : type,
            "val" : val
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
function toRecharge() {
    var val = $('#recharge option:selected').val();
    if (val == '0') {
        $('#point_give').css({
            display: 'none'
        });
        $('#point th').html('充值金额');
        $('#point input').attr("placeholder","请输入充值的基础积分");
    }else{
        $('#point_give').css({
            display: 'block'
        });
        $('#point th').html('基本积分');
        $('#point th').css({
            width: '120'
        });
        $('#point input').attr("placeholder","请输入用户打款金额");
    }
}
function recharge() {
    var type = $('#recharge option:selected').val();
    var point = $('input[name=recharge_point]').val();
    var point_give = $('input[name=recharge_point_give]').val();
    var rechargeLog = $('#rechargeLog').val();
    console.log(point);
    console.log(point_give);
    if (type == 0) {
        if (point == '') {
            layer.msg('充值项的金额不能为空!', function(){
            //关闭后的操作
            });
        }else{
            $.post('{{ url('admin/user/recharge') }}', {
                "_token" : '{{ csrf_token() }}',
                "type" : type,
                "price" : point,
                "uuid" : '{{ $data['user']->user_uuid }}',
                "log" : rechargeLog
            }, function(ret) {
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
        }
    }else{
        if (point == '' && point_give == '') {
            layer.msg('充值项的值不能为空!', function(){
            //关闭后的操作
            });
        }else{
            $.post('{{ url('admin/user/recharge') }}', {
                "_token" : '{{ csrf_token() }}',
                "type" : type,
                "point" : point,
                "point_give" : point_give,
                "uuid" : '{{ $data['user']->user_uuid }}',
                "log" : rechargeLog
            }, function(ret) {
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
        }
    }

}

function bank(id) {
    var name = $("input[name=namebank]").val();
    var bank_card = $("input[name=bank_card]").val();
    var bank_location = $("input[name=bank_location]").val();
    var bank_name = $("input[name=bank_name]").val();
    if (name == '') {
        layer.msg('请输入银行卡持卡人姓名', function(){
            //关闭后的操作
        });
    } else if (bank_card == '') {
        layer.msg('请输入银行卡卡号!', function(){
            //关闭后的操作
        });
    } else if (bank_location == '') {
        layer.msg('请输入银行卡开户行支行!', function(){
            //关闭后的操作
        });
    } else if (bank_name == '') {
        layer.msg('请输入银行卡开户行!', function(){
            //关闭后的操作
        });
    } else {
        $.post('{{ url('admin/user/bank/edit') }}/'+id,{
            "_token" : '{{ csrf_token() }}',
            "bank_card" : bank_card,
            "bank_location" : bank_location,
            "bank_name" : bank_name,
            "name" : name
        },function (ret) {
            var obj = $.parseJSON(ret)
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                setTimeout(function(){
                    location.reload()
                }, 1500);
            } else {
                layer.msg(obj.msg, function(){
                //关闭后的操作
                });
            }
        })
    }
}

function del(id) {
    layer.confirm('确定删除该银行卡?', {
        btn: ['取消','确定'] //按钮
    }, function(){
        layer.msg('已经取消');
    }, function(){
        $.post('{{ url('admin/user/bank/del') }}/'+id,{
            "_token" : '{{ csrf_token() }}'
        },function (ret) {
            var obj = $.parseJSON(ret)
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                setTimeout(function(){
                    location.reload()
                }, 1500);
            } else {
                layer.msg(obj.msg, function(){
                //关闭后的操作
                });
            }
        })
    });
}

function rank(uuid) { 
    var rank = $("#rank option:selected").val();
    var log = $('#rankLog').val();
    if (rank <= '{{ $data['user']->user_rank }}') {
        layer.msg('请重新选择正确的会员级别!', function(){
            //关闭后的操作
        });
    } else {
        $.post('{{ url('admin/user/payRank') }}',{
            "_token" : '{{csrf_token()}}',
            "rank" : rank,
            "uuid" : uuid,
            "log" : log
        },function (ret) {
            var obj = $.parseJSON(ret)
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                setTimeout(function(){
                    location.reload()
                }, 1500);
            } else {
                layer.msg(obj.msg, function(){
                //关闭后的操作
                });
            }
        });
    }
}

function updateJoinStatus(id) {
    // 修改加盟商状态
    $.post('{{ url('admin/join/status') }}/'+id,{
        "_token" : '{{ csrf_token() }}'
    },function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.msg(obj.msg);
                setTimeout(function(){
                    location.reload()
                }, 1500);
        }else{
            layer.msg(obj.msg);
        }
    })
}
</script>
@endsection