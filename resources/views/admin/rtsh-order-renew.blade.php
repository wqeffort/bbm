@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">融通四海</a> &raquo; 订单管理 &raquo; 创建订单
    </div>
    <div class="search_wrap">
            <form action="" method="post">
                <table class="search_tab">
                    <tr>
                        <th width="120">选择分类:</th>
                        <td>
                            <select id="type">
                                <option value="phone">手机号码</option>
                                <option value="name">用户姓名</option>
                                <option value="num">订单编号</option>
                            </select>
                        </td>
                        <td><input class="md" type="text" name="keywords" placeholder="电话号码或姓名或订单号"></td>
                        <td><a href="javascript:;" onclick="searchInfo()" class="submit">提交</a></td>
                    </tr>
                </table>
            </form>
        </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>选择项目</th>
                        <td>
                            <select id="objId" onchange="getObj()">
                                <option value="0">==请选择==</option>
                                @if ($obj->isNotEmpty())
                                    @foreach ($obj as $element)
                                        <option value="{{ $element->id }}">{{ $element->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>选择购买人</th>
                        <td>
                            <input type="number" name="phone" placeholder="请输入电话号码进行查询"><a style="border: 1px solid #EEE;padding: .3rem 1rem;background: #C40000;color: #FFF;" href="javascript:;" onclick="getUser()">查 询</a>

                        </td>
                    </tr>
                    <tr>
                        <th>身份信息</th>
                        <td id="userInfo">
                        </td>
                    </tr>
                    <tr>
                        <th>查询加盟商</th>
                        <td>
                            <input type="text" name="join" placeholder="输入加盟商姓名"><a style="border: 1px solid #EEE;padding: .3rem 1rem;background: #C40000;color: #FFF;" href="javascript:;" onclick="getJoin()">查 询</a>
                        </td>
                    </tr>
                    <tr>
                        <th>加盟商信息</th>
                        <td id="joinInfo">
                        </td>
                    </tr>
                    <tr>
                        <th>购买期限</th>
                        <td>
                            <input style="display: none;" type="radio" value="odds1" name="odds" id="odds_1">
                            <label style="padding: .2rem .5rem;color: #000;font-weight: bold;" for="odds_1" >三个月(<b style="color:#C40000;" id="odds1"></b>)</label>

                            <input style="display: none;" type="radio" value="odds2" name="odds" id="odds_2" checked="checked">
                            <label style="padding: .2rem .5rem;color: #000;font-weight: bold;" for="odds_2" >六个月(<b style="color:#C40000;" id="odds2"></b>)</label>
                        </td>
                    </tr>
                    <tr>
                        <th>购买金额</th>
                        <td>
                            <input type="number" name="price" id="price">
                        </td>
                    </tr>
                    <tr>
                        <th>附加金额</th>
                        <td>
                            <input type="number" name="cash" id="cash">
                            <p>总投资金额等于(购买金额加附加金额)</p>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>出款账户:</th>
                        <td>
                            <select name="account" id="account">
                                <option value="对公账户-中国银行">对公账户-中国银行</option>
                                <option value="对公账户-招商银行">对公账户-招商银行</option>
                                <option value="对公账户-工商银行">对公账户-工商银行</option>
                                <option value="周光磊-工商银行">周光磊-工商银行</option>
                                <option value="黄莎莎-中国银行">黄莎莎-中国银行</option>
                                <option value="蔡兴荣-中国银行">蔡兴荣-中国银行</option>
                                <option value="朱建锋-招商银行">朱建锋-招商银行</option>
                                <option value="朱建锋-农业银行">朱建锋-农业银行</option>
                                <option value="贺亮-招商银行">贺亮-招商银行</option>
                                <option value="朱建锋-中信银行">朱建锋-中信银行</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>预估收益</th>
                        <td id="total">
                        </td>  
                    </tr>
                    <tr>
                        <th>是否发送短信通知</th>
                        <td class="goods_attr_info">
                            <input type="radio" name="send_sms" value="1" checked="checked" id="n2">
                            <label for="n2">发送短信</label>
                            <input type="radio" checked="checked" name="send_sms" value="0" id="n1">
                            <label for="n1">不发送短信</label>
                        </td>
                    </tr>
                    <tr>
                        <th>工作日志</th>
                        <td>
                            <textarea class="lg" id="log"></textarea>
                            <p>用于工作记录交接,限制长度200中文字符</p>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <a href="javascript:;" onclick="sub()" class="submit">提交</a>
                            <input type="button" class="back" onclick="history.go(-1)" value="返回">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

<script type="text/javascript">
var balance = '';
    function getUser() {
        var phone = $('input[name=phone]').val();
        if (isPhone(phone)) {
            $.post('{{ url('admin/rtsh/select/user') }}', {
                "_token" : '{{ csrf_token() }}',
                "phone" : phone
            }, function (ret) {
                var obj = $.parseJSON(ret);
                // console.log(obj);
                if (obj.status == 'success') {
                    balance = obj.data.rtsh_frozen;
                    var html = '<ul><p style="display:none;" id="uuid">'+obj.data.user_uuid+'</p><li>用户姓名:<b>'+obj.data.user_name+'</b></li><li>身份证号码:<b>'+obj.data.user_uid+'</b></li><li>冻结余额:<b>'+obj.data.rtsh_frozen+'</b> 元</li><li><img style="width:10rem;" src="{{ url('/') }}/'+obj.data.user_uid_a+'"></li><li><img style="width:10rem;"  src="{{ url('/') }}/'+obj.data.user_uid_b+'"></li></ul>';
                    $('#userInfo').html(html);
                        // if (obj.data.join) {
                        //     $('#joinInfo').html('<p id="join_name">'+obj.data.join+'</p><span style="display:none;" id="join_uuid">'+obj.data.join_uuid+'</span>');
                        // }else{
                        //     $('#joinInfo').html('<p>未查寻到加盟商</p>');
                        // }
                }else{
                    layer.msg(obj.msg,function () {
                    });
                }
            })
        }else{
            layer.msg('您输入的手机号码格式不正确!',function () {
            });
        }
    }

    function getJoin() {
        var join = $('input[name=join]').val();
        $.post('{{ url('admin/rtsh/select/join') }}', {
            "_token" : '{{ csrf_token() }}',
            "join" : join
        }, function (ret) {
            var obj = $.parseJSON(ret);
                // console.log(obj);
            if (obj.status == 'success') {
                var html = '<ul><p style="display:none;" id="uuid">'+obj.data.user_uuid+'</p><li>加盟商姓名:<b>'+obj.data.user_name+'</b></li><li>加盟商电话:<b>'+obj.data.user_phone+'</b></li><li>身份证号码:<b>'+obj.data.user_uid+'</b></li><li><img style="width:10rem;" src="{{ url('/') }}/'+obj.data.user_uid_a+'"></li><li><img style="width:10rem;"  src="{{ url('/') }}/'+obj.data.user_uid_b+'"></li></ul>';
                $('#joinInfo').html('<p id="join_name" style="display:none;">'+obj.data.user_name+'</p><span style="display:none;" id="join_uuid">'+obj.data.user_uuid+'</span>'+html);
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        })
    }

    function getObj() {
        var objId = $("#objId  option:selected").val();
        if (objId != '0') {
            loading();
            // 获取到该项目的收益
            $.post('{{ url('admin/rtsh/select/obj') }}', {
                "_token" : '{{ csrf_token() }}',
                "id" : objId
            }, function(ret) {
                layer.close(loadingBox);
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    $('#odds1').html(obj.data.odds_1);
                    $('#odds2').html(obj.data.odds_2);
                }else{
                    layer.msg(obj.msg,function () {
                    });
                }
            });
        }else{
            $('#odds1').html('');
            $('#odds2').html('');
            $('#price').val('');
            $('#total').html('');
        }
    }


$("#price").bind("input propertychange",function(){
    // 输入的时候检查是否已经选择了项目.
    var type = $("input[name='odds']:checked").val();
    var odds = $("#"+type+"").text();
    // 获取到附加的金额
    var cash = $('input[name=cash]').val();
    if (!cash) {
        cash = 0;
    }
    if (odds == '') {
        layer.msg('请先选择项目',function () {
            $('#price').val('');
            $('#cash').val('');
        });
    }else{
        if (type == 'odds1') {
            var price = parseInt($(this).val()) + parseInt(cash);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/4;
        }else if (type == 'odds2') {
            var price = parseInt($(this).val()) + parseInt(cash);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/2;
        }
        // 赋值操作
        $('#total').html('<p>投资总额:<b>'+price+'</b></p><span>最后收益: <b style="color:#C40000;">'+total+'</b> 元</span>');
    }
});

$("#cash").bind("input propertychange",function(){
    // 输入的时候检查是否已经选择了项目.
    var type = $("input[name='odds']:checked").val();
    var odds = $("#"+type+"").text();
    // 获取到附加的金额
    var frozen = $('input[name=price]').val();
    if (!frozen) {
        frozen = 0;
    }
    if (odds == '') {
        layer.msg('请先选择项目',function () {
            $('#cash').val('');
            $('#price').val('');
        });
    }else{
        if (type == 'odds1') {
            var price = parseInt($(this).val()) + parseInt(frozen);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/4;
        }else if (type == 'odds2') {
            var price = parseInt($(this).val()) + parseInt(frozen);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/2;
        }
        // 赋值操作
        $('#total').html('<p>投资总额:<b>'+price+'</b></p><span>最后收益: <b style="color:#C40000;">'+total+'</b> 元</span>');
    }
});


function sub() {
    var obj_id = $("#objId  option:selected").val();
    var account = $('#account option:selected').val();
    var log = $("#log").val();
    var price = $("#price").val();
    var cash = $("#cash").val();
    var time = $("input[name='odds']:checked").val();
    var odds = $("#"+time+"").text();
    var uuid = $("#uuid").html();
    if ($("#join_name").text()) {
        var join_name = $("#join_name").text();    
    } else {
        var join_name = '';
    }
    if ($("#join_uuid").text()) {
        var join_uuid = $("#join_uuid").text();    
    } else {
        var join_uuid = '';
    }
    
    var send_sms = $("input[name=send_sms]:checked").val();
    if (uuid == '') {
        layer.msg('请查询用户信息后再提交!',function () {
        });
    }else if (obj_id == '0') {
        layer.msg('请选择项目!',function () {
        });
    }else if (price == '' && cash == '') {
        layer.msg('请输入购买金额!',function () {
        });
    }else if (parseInt(balance) - parseInt(price) < 0) {
        layer.msg('购买金额大于冻结余额!',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/order/renew') }}',{
            "_token" : '{{ csrf_token() }}',
            "obj_id" : obj_id,
            "log" : log,
            "uuid" : uuid,
            "join_uuid" : join_uuid,
            "join_name" : join_name,
            "time" : time,
            "odds" : odds,
            "price" : price,
            "account" : account,
            "cash" : cash,
            "send_sms" :send_sms
        },function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                self.location=document.referrer;
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        });
    }
}

function searchInfo() {
    var type = $("#type  option:selected").val();
    var text = $("input[name=keywords]").val();
    if (text == '') {
        layer.msg('查询的内容不能为空!', function(){
            // location.reload();
        });
    }else if (type == 'num') {
        layer.msg('暂不支持订单号查询', function(){
            // location.reload();
        });
    }else{
        loading();
        $.post('{{ url('admin/search/user') }}', {
            "_token" : '{{ csrf_token() }}',
            "text" : text
        }, function(ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                console.log(obj.data);
                var html = '';
                $.each(obj.data, function(index, val) {
                    var rank = '';
                    switch(val.user_rank) {
                        case '0':
                            rank = '普通用户';
                            break;
                        case '1':
                            rank = '体验会员';
                            break;
                        case '2':
                            rank = '男爵会员';
                            break;
                        case '3':
                            rank = '子爵会员';
                            break;
                        case '4':
                            rank = '伯爵会员';
                            break;
                        case '5':
                            rank = '侯爵会员';
                            break;
                        case '6':
                            rank = '公爵会员';
                            break;
                        case '10':
                            rank = '内部员工';
                    }
                    html += '<tr><td class="tc"><img class="user_pic" src="'+val.user_pic+'"></td><td class="tc">'+val.user_nickname+'</td><td class="tc">'+val.user_name+'</td><td class="tc">'+val.user_phone+'</td><td class="tc">'+rank+'</td><td class="tc">'+val.rtsh_bond+'</td><td class="tc">'+val.rtsh_frozen+'</td><td class="tc">'+val.updated_at+'</td><td class="tc"><a href="{{ url('admin/rtsh/user') }}/'+val.user_uuid+'" target="main"><i class="fa fa-search"></i></a></td></tr>'
                });
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    shadeClose: true,
                    area: ['700px', '450px'],
                    skin: 'layui-layer-rim',
                    content: '<table class="list_tab"><tr><th class="tc">头像</th><th class="tc">昵称</th><th class="tc">姓名</th><th class="tc">电话</th><th class="tc">用户等级</th><th class="tc">债权账户</th><th class="tc">冻结账户</th><th class="tc">上次登录</th><th class="tc">查看</th></tr>'+html+'</table>'
                });
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










































