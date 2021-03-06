@extends('lib.service.header')
@section('body')
    <a class="layui-btn layui-btn-sm" style="position: fixed;right: 1rem;top: .25rem;background: #ff5604;z-index: 999;" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ 刷新窗口</i>
    </a>
    <div class="weadmin-body">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
            <legend>APP Ad Add</legend>
        </fieldset>
        <div class="layui-row">
            <form class="layui-form">

                <div class="layui-form-item">
                    <label class="layui-form-label">广告标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" required value="{{ $ad->title }}" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">广告图片</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" disabled="disabled" value="{{ $ad->img }}" name="img">
                    </div>
                    <button type="button" class="layui-btn" id="img">
                        <i class="layui-icon">&#xe67c;</i>上传图片
                    </button>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">OSS地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="oss" required value="{{ $ad->img }}" disabled="disabled" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">链接地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="link" required value="{{ $ad->link }}"  class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <a href="javascript:;" onclick="sub({{ $ad->id }})" class="layui-btn" lay-submit >立即提交</a>
                        <button type="reset" class="layui-btn layui-btn-primary">重置表单</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
<script>
layui.use('upload', function(){
    var upload = layui.upload;
    //执行实例
    var uploadInst = upload.render({
        elem: '#img' //绑定元素
        ,url: '{{ url('service/img') }}' //上传接口
        ,data: {
            "_token" : '{{ csrf_token() }}'
        }
        ,accept : 'images'
        ,size : '1000'
        ,done: function(ret){
            if (ret.status == 'success') {
                layer.msg(ret.msg)
                $('input[name=img]').val(ret.data.url)
                $('input[name=oss]').val(ret.data.url)
            }else{
                layer.msg(ret.msg, function(){
                    //关闭后的操作
                });
            }
        }
        ,error: function(){
            //请求异常回调
        }
    });
});

function sub(id) {
    var title = $('input[name=title]').val();
    var img = $('input[name=img]').val();
    var link = $('input[name=link]').val();
    loading()
    if (title && img && link) {
        $.post('{{ url('service/app/ad/edit') }}/'+id,{
            "_token" : '{{ csrf_token() }}',
            "title" : title,
            "img" : img,
            "link" : link
        },function (ret) {
            console.log(ret)
            var obj = $.parseJSON(ret);
            layer.close(loadingBox);
            if (obj.status == 'success') {
                layer.msg(obj.msg);
            }else{
                layer.msg(obj.msg, function(){
                    //关闭后的操作
                });
            }
        })
    }else{
        layer.msg('参数缺失,请填写完表单后再进行提交', function(){
            layer.close(loadingBox)
        });
    }
}
</script>
@endsection