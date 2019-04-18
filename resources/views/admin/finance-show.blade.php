@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>用户姓名:</th>
                        <td>
                            <p>{{ $order->user_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>联系电话:</th>
                        <td>
                            <p>{{ $order->user_phone }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>身份证照片:</th>
                        <td style="background: #EEE;">
                            <img src="{{ url($order->user_uid_a) }}" alt="" style="height: 175px">
                        </td>
                    </tr>
                    <tr>
                        <th>打款金额:</th>
                        <td>
                            <p><b style="color:#C40000;">{{ $order->price }}</b> 元</p>
                        </td>
                    </tr>
                     <tr>
                        <th></th>
                        <td style="background: #EEE;">
                            <img src="{{ asset($order->img) }}" alt="" id="art_thumb_img" style="max-height: 175px">
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>银行回单:</th>
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
                                <input type="text" class="md" name="art_thumb" value="{{ $order->img }}">
                                <input id="file_upload" name="file_upload" type="file" multiple="true">
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>操作状态:</th>
                        <td class="goods_attr_info">
                            {{-- <input type="radio" name="agree" value="0" checked="checked" id="n2">
                            <label for="n2">延后审批</label> --}}
                            <input type="radio" checked="checked" name="agree" value="1" id="n1">
                            <label for="n1">完成审批</label>
                        </td>
                    </tr>
                    <tr>
                        <th>工作日志：</th>
                        <td>
                            <textarea class="lg" id="log">{{ $order->log }}</textarea>
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
function sub(id) {
    var img = $("input[name=art_thumb]").val();
    var agree = $("input[name=agree]:checked").val();
    var log = $("#log").val();
    if (img == '') {
        layer.msg('银行回单凭证不能为空',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/finance') }}/{{ $order->id }}/edit',{
            "_token" : '{{ csrf_token() }}',
            "img" : img,
            "log" : log,
            "agree" : agree
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










































