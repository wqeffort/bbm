@extends('lib.admin.header')
@section('body')
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>文章标题:</th>
                        <td>
                            <input type="text" value="{{ $article->title }}" name="title">
                            <span><i class="fa fa-exclamation-circle yellow"></i>文章标题请勿超过20个中文字符</span>
                        </td>
                    </tr>
                    <tr>
                        <th>文章分类:</th>
                        <td>
                            <select id="cate_id">
                                @foreach ($cate as $element)
                                    <option value="{{ $article->cate_id }}">==不进行修改==</option>
                                    <option value="{{ $element->id }}">{{ $element->name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>文章封面:</th>
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
                                <input type="text" class="md" name="art_thumb" value="{{ $article->img }}">
                                <input id="file_upload" name="file_upload" type="file" multiple="true">
                        </td>
                    </tr>
                    <tr>
                        <th>缩略图</th>
                        <td>
                            <img src="{{ url($article->img) }}" alt="" id="art_thumb_img" style="width: 100px;height: 100px">
                        </td>
                    </tr>
                    <tr>
                         <th>文章内容</th>
                         <td>
                             <script id="editor" name="goods_desc" type="text/plain" style="width:1000px;height:500px;">{!! $article->text !!}</script>
                            <script type="text/javascript">
                                var ue = UE.getEditor('editor');
                            </script>
                         </td>
                    </tr>
                    <tr>
                        <th>工作日志：</th>
                        <td>
                            <textarea class="lg" id="log">
                                {{ $article->log }}
                            </textarea>
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
    var title = $("input[name=title]").val();
    var log = $("#log").val();
    var img = $("input[name=art_thumb]").val();
    var text = UE.getEditor('editor').getContent();
    var cate_id = $("#cate_id  option:selected").val();
    if (title == '') {
        layer.msg('文章标题不能为空',function () {
        });
    }else if (img == '') {
        layer.msg('文章缩略图不能为空',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/article/edit') }}/{{ $article->id }}',{
            "_token" : '{{ csrf_token() }}',
            "title" : title,
            "log" : log,
            "img" : img,
            "text" : text,
            "cate_id" : cate_id
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










































