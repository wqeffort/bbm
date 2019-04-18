@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th width="120">所属分类:</th>
                        <td>
                            <select id="category_pid">
                                <option value="0">==请选择==</option>
                                @if ($cate->isNotEmpty())
                                    @foreach ($cate as $element)
                                        <option value="{{ $element->id }}">{{ $element->category_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span><i class="fa fa-exclamation-circle yellow"></i>不选择则为顶级分类</span>
                        </td>
                    </tr>
                    <tr>
                        <th>分类名称:</th>
                        <td>
                            <input type="text" name="category_name">
                            <span><i class="fa fa-exclamation-circle yellow"></i>分类名称长度请勿超过6个中文字符</span>
                        </td>
                    </tr>
                    <tr>
                        <th>分类描述:</th>
                        <td>
                            <input type="text" class="md" name="category_title">
                            <span><i class="fa fa-exclamation-circle yellow"></i>分类描述长度请勿超过20个中文字符</span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>分类图标:</th>
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
                            <img src="" alt="" id="art_thumb_img" style="width: 175px;height: 100px">
                        </td>
                    </tr>
                    <tr>
                        <th>工作日志：</th>
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
function sub() {
    var category_pid = $("#category_pid  option:selected").val();
    var category_name = $("input[name=category_name]").val();
    var category_title = $("input[name=category_title]").val();
    var log = $("#log").val();
    var category_pic = $("input[name=art_thumb]").val();
    console.log(category_pid);
    console.log(category_name);
    console.log(category_title);
    console.log(log);
    console.log(category_pic);
    if (category_name == '') {
        layer.msg('分类名称不能为空',function () {
        });
    }else if (category_title == '') {
        layer.msg('分类描述不能为空',function () {
        });
    }else if (category_pic == '') {
        layer.msg('分类图片不能为空',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/category/create') }}',{
            "_token" : '{{ csrf_token() }}',
            "category_pid" : category_pid,
            "category_name" : category_name,
            "category_title" : category_title,
            "category_pic" : category_pic,
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










































