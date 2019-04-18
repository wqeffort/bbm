@extends('lib.home.header')
@section('body')
{{-- {{ dd(session('app')) }} --}}
<style type="text/css">
h3 {
    padding: .5rem;
    font-size: 1.5rem;
}
.info ul {
    display: flex;
    justify-content: space-between;
    line-height: 2rem;
    padding: 0 1rem;
}
.info ul li dl dd img {
    width: 2rem;
    border-radius: 50%;
}
.info dl {
    display: flex;
    justify-content: space-between;
    line-height: 2rem;
}
.info dl dd {
    padding: 0 .5rem;
}
.info dl dd i {
    font-size: 1.2rem;
}
.infoBtn {
    padding: .2rem .5rem;
    background: #C40000;
    color: #FFF;
    border-radius: 1rem;
}
.text {
    padding: 1rem .5rem;
}
.text img {
    width: 100% !important;
    height: 100% !important;
}
</style>
<h3>
    {{ $article->title }}
</h3>
<div class="info">
    <ul>
        <li>
            <dl>
                <dd>
                    <img src="http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLibbVMvILCmbf4tL791qHFB4QConhKVFsQEIMzKsbnGFmic6ib1f4wZKZI8PDtwhKLxFZvqxia2hwUYg/132">
                </dd>
                <dd>
                    <span>JaClub</span>
                </dd>
            </dl>
        </li>
        <li>
            <span class="infoBtn"><i class="fa fa-thumbs-o-up"></i> 支 持</span>
        </li>
    </ul>
    <div class="text">
        {!! $article->text !!}
    </div>
</div>
<script type="text/javascript">
// 提交添加地址的表单
function addAds() {
    var name = $('input[name=name]').val();
    var phone = $('input[name=phone]').val();
    var city = $('input[name=city]').val();
    var ads = $('input[name=ads]').val();
    $.post('{{ url('addAds') }}', {
        "_token" : '{{ csrf_token() }}',
        "name" : name,
        "phone" : phone,
        "city" : city,
        "ads" : ads
    }, function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            location.href = '{{ url('adsList') }}';
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}

</script>


@endsection






































