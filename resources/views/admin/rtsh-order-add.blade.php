@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">融通四海</a> &raquo; 订单管理 &raquo; 创建订单
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
                            <input type="number" name="phone" placeholder="请输入电话号码进行查询"><a style="border: 1px solid #EEE;padding: .3rem 1rem;background: #666;color: #FFF;" href="javascript:;" onclick="getUser()">查 询</span>

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
                            <p id="total"></p>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>打款凭证:</th>
                        <td>
                            <script type="text/javascript">
                                $(function() {
                                    $('#file_upload').uploadify({
                                        'buttonText' : '上 传 图 片',
                                        'formData'     : {
                                            '_token'     : '{{ csrf_token() }}'
                                        },
                                        'swf'      : '{{ asset('class/uploadify/uploadify.swf') }}',
                                        'uploader' : '{{ url('img') }}',
                                        'onUploadSuccess' : function(file, data, response) {
                                            $('input[name=art_thumb]').val(data);
                                            $('#art_thumb_img').attr('src','/'+data);
                                        }
                                    });
                                });
                            </script>
                                <input type="text" class="md" name="art_thumb" value="">
                                <input id="file_upload" name="file_upload" type="file" multiple="true">
                        </td>
                    </tr>
                    <tr>
                        <th>缩略图</th>
                        <td>
                            <img src="" alt="" id="art_thumb_img" style="width: 100px;height: 100px">
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
                    var html = '<ul><p style="display:none;" id="uuid">'+obj.data.user_uuid+'</p><li>用户姓名:<b>'+obj.data.user_name+'</b></li><li>身份证号码:<b>'+obj.data.user_uid+'</b></li><li><img style="width:10rem;" src="{{ url('/') }}/'+obj.data.user_uid_a+'"></li><li><img style="width:10rem;"  src="{{ url('/') }}/'+obj.data.user_uid_b+'"></li></ul>';
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
    var type = $("input[name='odds']:checked").val();
    var odds = $("#"+type+"").text();
    if (odds == '') {
        layer.msg('请先选择项目',function () {
            $('#price').val('');
        });
    }else{
        if (type == 'odds1') {
            var price = $(this).val();
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/4;
        }else if (type == 'odds2') {
            var price = $(this).val();
            var total = parseInt(price) * (parseFloat(odds) * 1000) /1000/2;
        }
        // 赋值操作
        $('#total').html('最后收益: <b style="color:#C40000;">'+total+'</b> 元');
    }
});


function sub() {
    var obj_id = $("#objId  option:selected").val();
    var log = $("#log").val();
    var img = $("input[name=art_thumb]").val();
    var price = $("#price").val();
    var time = $("input[name='odds']:checked").val();
    var odds = $("#"+time+"").text();
    var uuid = $("#uuid").html();
    var join_name = $("#join_name").html();
    var join_uuid = $("#join_uuid").html();
    if (uuid == '') {
        layer.msg('请查询用户信息后再提交!',function () {
        });
    }else if (obj_id == '0') {
        layer.msg('请选择项目!',function () {
        });
    }else if (img == '') {
        layer.msg('请上传打款凭证',function () {
        });
    }else if (price == '') {
        layer.msg('请输入购买金额!',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/order/add') }}',{
            "_token" : '{{ csrf_token() }}',
            "obj_id" : obj_id,
            "log" : log,
            "img" : img,
            "uuid" : uuid,
            "join_uuid" : join_uuid,
            "join_name" : join_name,
            "time" : time,
            "odds" : odds,
            "price" : price
        },function (ret) {
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
                history.back(-1);
            }else{
                layer.msg(obj.msg,function () {
                });
            }
        });
    }
}
</script>
@endsection










































