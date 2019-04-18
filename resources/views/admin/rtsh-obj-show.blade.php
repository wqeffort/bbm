@extends('lib.admin.header')
@section('body')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">融通四海</a> &raquo; 项目管理 &raquo; 编辑项目
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th>项目名称</th>
                        <td>
                            <input type="text" value="{{ $obj->title }}" name="title">
                            <span><i class="fa fa-exclamation-circle yellow"></i>请输入项目标题</span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="require">*</i>项目封面:</th>
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
                                <input type="text" class="md" name="art_thumb" value="{{ $obj->img }}">
                                <input id="file_upload" name="file_upload" type="file" multiple="true">
                        </td>
                    </tr>
                    <tr>
                        <th>缩略图</th>
                        <td>
                            <img src="{{ url($obj->img) }}" alt="" id="art_thumb_img" style="width: 100px;height: 100px">
                        </td>
                    </tr>
                    <tr>
                         <th>项目内容</th>
                         <td>
                             <script id="editor" name="goods_desc" type="text/plain" style="width:1000px;height:500px;">
                                {!! $obj->desc !!}
                             </script>
                            <script type="text/javascript">
                                var ue = UE.getEditor('editor');
                            </script>
                         </td>
                    </tr>
                    <tr>
                        <th>单期年化收益(三个月)</th>
                        <td>
                            <input type="number" value="{{ $obj->odds_1 }}" name="odds_1">
                        </td>
                    </tr>
                    <tr>
                        <th>两期年化收益(六个月)</th>
                        <td>
                            <input type="number" value="{{ $obj->odds_2 }}" name="odds_2">
                        </td>
                    </tr>
                    <tr>
                        <th>开始时间</th>
                        <td>
                            <input type="date" value="{{ substr($obj->start, 0,10) }}" name="start">
                        </td>
                    </tr>
                    <tr>
                        <th>工作日志</th>
                        <td>
                            <textarea class="lg" id="log">{{ $obj->log }}</textarea>
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
    var start = $("input[name=start]").val();
    var end = $("input[name=end]").val();
    var text = UE.getEditor('editor').getContent();
    var odds_1 = $("input[name=odds_1]").val();
    var odds_2 = $("input[name=odds_2]").val();
    if (title == '') {
        layer.msg('项目标题不能为空!',function () {
        });
    }else if (img == '') {
        layer.msg('项目缩略图不能为空!',function () {
        });
    }else if (start == '') {
        layer.msg('项目开始时间不能为空!',function () {
        });
    }else if (odds_1 == '') {
        layer.msg('三个月的收益率不能为空!',function () {
        });
    }else if (odds_2 == '') {
        layer.msg('六个月的收益率不能为空!',function () {
        });
    }else{
        loading();
        $.post('{{ url('admin/rtsh/edit') }}/{{ $obj->id }}',{
            "_token" : '{{ csrf_token() }}',
            "title" : title,
            "log" : log,
            "img" : img,
            "text" : text,
            "start" : start,
            "odds_1" : odds_1,
            "odds_2" : odds_2
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










































