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
                        <select>
                            <option value="">手机号码</option>
                            <option value="">用户姓名</option>
                            <option value="">订单编号</option>
                        </select>
                    </td>
                    <th width="70">关键字:</th>
                    <td><input type="text" name="keywords" placeholder="关键字"></td>
                    <td><input type="submit" name="sub" value="查询"></td>
                    <p>目前暂时支持手机号码查询</p>
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
                        <th>项目名称</th>
                        <td>
                            {{ $data->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>身份信息</th>
                        <td id="userInfo">
                            <ul>
                                <p style="display:none;" id="uuid">{{ $data->uuid }}</p>
                                    <li>用户姓名:<b>{{ $data->user_name }}</b></li>
                                    <li>身份证号码:<b>{{ $data->user_uid }}</b></li>
                                    <li>账户余额:<b>{{ $data->rtsh_bond }}</b></li>
                                    <li>冻结余额:<b id="balance">{{ $data->rtsh_frozen }}</b> 元</li>
                                    <li><img style="width:10rem;" src="{{ url($data->user_uid_a) }}"></li>
                                    <li><img style="width:10rem;"  src="{{ url($data->user_uid_b) }}">
                                    </li>
                                </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>加盟商信息</th>
                        <td id="joinInfo">
                            <p id="join_name" style="display:block;">{{ $data->join_name }}</p>
                            <span style="display:none;" id="join_uuid">{{ $data->join_uuid }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>购买期限</th>
                        <td>
                            <input type="number" name="time" id="time" value="{{ $data->time }}">期
                        </td>
                    </tr>
                    <tr>
                        <th>年化收益</th>
                        <td id="odds">
                            <input type="number" name="odds" id="odds" value="{{ $data->odds }}">
                        </td>
                    </tr>
                    <tr>
                        <th>购买金额</th>
                        <td>
                            <input type="number" name="price" id="price" value="{{ $data->price - $data->cash }}">
                        </td>
                    </tr>
                    <tr>
                        <th>附加金额</th>
                        <td>
                            <input type="number" name="cash" id="cash" value="{{ $data->cash }}">
                            <p>总投资金额等于(购买金额加附加金额)</p>
                        </td>
                    </tr>
                    <tr>
                            <th><i class="require">*</i>入款账户:</th>
                            <td>
                                <select name="account" id="account">
                                        <option value="{{ $data->account }}">{{ $data->account }}</option>
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
                    <tr style="display:none;">
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
                        <textarea class="lg" id="log">{{ $data->log }}</textarea>
                            <p>用于工作记录交接,限制长度200中文字符</p>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <a href="javascript:;" onclick="sub()" class="submit">修改</a>
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
                    var html = '<ul><p style="display:none;" id="uuid">'+obj.data.user_uuid+'</p><li>用户姓名:<b>'+obj.data.user_name+'</b></li><li>身份证号码:<b>'+obj.data.user_uid+'</b></li><li>冻结余额:<b>'+obj.data.rtsh_frozen+'</b> 元</li><li><img style="width:10rem;" src="{{ url('/') }}/'+obj.data.user_uid_a+'"></li><li><img style="width:10rem;"  src="{{ url('/') }}/'+obj.data.user_uid_a+'"></li></ul>';
                    $('#userInfo').html(html);
                        if (obj.data.join) {
                            $('#joinInfo').html('<p id="join_name">'+obj.data.join+'</p><span style="display:none;" id="join_uuid">'+obj.data.join_uuid+'</span>');
                        }else{
                            $('#joinInfo').html('<p>未查寻到加盟商</p>');
                        }
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
                var html = '<ul><p style="display:none;" id="uuid">'+obj.data.user_uuid+'</p><li>加盟商姓名:<b>'+obj.data.user_name+'</b></li><li>加盟商电话:<b>'+obj.data.user_phone+'</b></li><li>身份证号码:<b>'+obj.data.user_uid+'</b></li><li><img style="width:10rem;" src="{{ url('/') }}/'+obj.data.user_uid_a+'"></li><li><img style="width:10rem;"  src="{{ url('/') }}/'+obj.data.user_uid_a+'"></li></ul>';
                $('#joinInfo').html('<p id="join_name" style="display:none;>'+obj.data.user_name+'</p><span style="display:none;" id="join_uuid">'+obj.data.join_uuid+'</span>'+html);
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
    var type = $('input[name=time]').val();
    var odds = $("input[name=odds]").val();
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
        if (type == 1) {
            var price = parseInt($(this).val()) + parseInt(cash);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/4;
        }else{
            var price = parseInt($(this).val()) + parseInt(cash);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/2;
        }
        // 赋值操作
        $('#total').html('<p>投资总额:<b>'+price+'</b></p><span>最后收益: <b style="color:#C40000;">'+total+'</b> 元</span>');
    }
});

$("#cash").bind("input propertychange",function(){
    // 输入的时候检查是否已经选择了项目.
    var type = $('input[name=time]').val();
    var odds = $("input[name=odds]").val();
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
        if (type == 1) {
            var price = parseInt($(this).val()) + parseInt(frozen);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/4;
        }else{
            var price = parseInt($(this).val()) + parseInt(frozen);
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/2;
        }
        // 赋值操作
        $('#total').html('<p>投资总额:<b>'+price+'</b></p><span>最后收益: <b style="color:#C40000;">'+total+'</b> 元</span>');
    }
});


function sub() {
    var log = $("#log").val();
    var account = $('#account option:selected').val();
    var price = $("#price").val();
    var cash = $("#cash").val();
    var time = $("input[name=time]").val();
    var odds = $("input[name=odds]").val();
    var uuid = $("#uuid").html();
    var join_name = $("#join_name").html();
    var join_uuid = $("#join_uuid").html();
    var send_sms = $("input[name=send_sms]:checked").val();
    if (uuid == '') {
        layer.msg('请查询用户信息后再提交!',function () {
        });
    }else if (price == '' && cash == '') {
        layer.msg('请输入购买金额!',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/order/view') }}/{{ $data->num }}',{
            "_token" : '{{ csrf_token() }}',
            "log" : log,
            "uuid" : uuid,
            "join_uuid" : join_uuid,
            "join_name" : join_name,
            "time" : time,
            "odds" : odds,
            "price" : price,
            "cash" : cash,
            "account" : account,
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
</script>
@endsection










































