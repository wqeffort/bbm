@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>品牌名称:</th>
                        <td>
                            <input type="text" name="brand_name" value="{{ $brand->brand_name }}">
                            <span><i class="fa fa-exclamation-circle yellow"></i>品牌名称长度请勿超过6个中文字符</span>
                        </td>
                    </tr>
                    <tr>
                        <th>品牌描述:</th>
                        <td>
                            <input type="text" class="md" name="brand_title" value="{{ $brand->brand_title }}">
                            <span><i class="fa fa-exclamation-circle yellow"></i>品牌描述长度请勿超过20个中文字符</span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>品牌图标:</th>
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
                                <input type="text" class="md" name="art_thumb" value="{{ $brand->brand_pic }}">
                                <input id="file_upload" name="file_upload" type="file" multiple="true">
                        </td>
                    </tr>
                    <tr>
                        <th>缩略图</th>
                        <td>
                            <img src="{{ url($brand->brand_pic) }}" alt="" id="art_thumb_img" style="width: 175px;height: 175px">
                        </td>
                    </tr>
                    <tr>
                        <th>工作日志：</th>
                        <td>
                            <textarea class="lg" id="log">{{ $brand->log }}</textarea>
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
    var brand_name = $("input[name=brand_name]").val();
    var brand_title = $("input[name=brand_title]").val();
    var log = $("#log").val();
    var brand_pic = $("input[name=art_thumb]").val();
    console.log(brand_name);
    console.log(brand_title);
    console.log(log);
    console.log(brand_pic);
    if (brand_name == '') {
        layer.msg('品牌名称不能为空',function () {
        });
    }else if (brand_title == '') {
        layer.msg('品牌描述不能为空',function () {
        });
    }else if (brand_pic == '') {
        layer.msg('品牌图片不能为空',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/brand/edit') }}/{{ $brand->id }}',{
            "_token" : '{{ csrf_token() }}',
            "brand_name" : brand_name,
            "brand_title" : brand_title,
            "brand_pic" : brand_pic,
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










































