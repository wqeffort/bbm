@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>位置描述:</th>
                        <td>
                            <input type="text" name="title" value="{{ $ad->title }}">
                            <span><i class="fa fa-exclamation-circle yellow"></i>用于描述广告位的叙述</span>
                        </td>
                    </tr>
                    <tr>
                        <th>广告描述:</th>
                        <td>
                            <input type="text" class="md" name="alt" value="{{ $ad->alt }}">
                            <span><i class="fa fa-exclamation-circle yellow"></i>广告位内容描述</span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>广告图片:</th>
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
                                <input type="text" class="md" name="art_thumb" value="{{ $ad->img }}">
                                <input id="file_upload" name="file_upload" type="file" multiple="true">
                        </td>
                    </tr>
                    <tr>
                        <th>缩略图:</th>
                        <td style="background: #EEE;">
                            <img src="{{ url($ad->img) }}" alt="" id="art_thumb_img" style="width:640px;height:312px;">
                        </td>
                    </tr>
                    <tr>
                        <th>链接地址:</th>
                        <td>
                            <input type="text" class="md" name="url" value="{{ $ad->url }}">
                            <p>如不做跳转(链接),请直接输入"#"即可</p>
                        </td>
                    </tr>
                    <tr>
                        <th>工作日志：</th>
                        <td>
                            <textarea class="lg" id="log">{{ $ad->log }}</textarea>
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
    var title = $("input[name=title]").val();
    var alt = $("input[name=alt]").val();
    var url = $("input[name=url]").val();
    var log = $("#log").val();
    var img = $("input[name=art_thumb]").val();

    if (title == '') {
        layer.msg('广告位位置描述禁止为空',function () {
        });
    }else if (alt == '') {
        layer.msg('广告内容描述不能为空',function () {
        });
    }else if (img == '') {
        layer.msg('广告图片不能为空',function () {
        });
    }else if (url == '') {
        layer.msg('广告位链接地址不能为空',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/ad/edit') }}/{{ $ad->id }}',{
            "_token" : '{{ csrf_token() }}',
            "title" : title,
            "alt" : alt,
            "url" : url,
            "img" : img,
            "log" : log
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










































