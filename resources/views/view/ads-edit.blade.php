@extends('lib.view.header')
@section('body')
<script type="text/javascript" src="{{ asset('js/Popt.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/city.json.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/citySet.js') }}"></script>
    <style type="text/css">
        dt {
            text-align: center;
            background: #6495ed;
            color: #FFF;
            padding: .7rem 0;
            font-size: 1rem;
        }
        dd {
            margin: 1rem;
            border-bottom: 1px solid #999;
            display: flex;
            justify-content: space-between;
        }
        input {
            text-align: center;
            border:none;
            border-radius: none;
        }
        .fund {
            position: fixed;
            z-index: 999;
            width: 2rem;
            height: 2rem;
            background: #6395ed;
            opacity: .8;
            text-align: center;
            line-height: 2rem;
            bottom: 8rem;
            left: 0;
        }
        .fund a {
            color: #FFF;
            font-size: 1.5rem;
        }
    </style>

<dl>
    <dt>修改收货地址</dt>
    <dd>收件人姓名: <input style="border: none;width: 65%;" type="text" name="name" placeholder="请输入联系人姓名" value="{{ $ads->name }}"></dd>
    <dd>联系电话: <input style="border: none;width: 65%;" type="number" name="phone" placeholder="请输入联系人电话"  value="{{ $ads->phone }}"></dd>
    <dd>
        选择省市区:
        <input style="border: none;width: 65%;" class="input" name="city" id="city" type="text" placeholder="点击选择省市区" autocomplete="off" readonly="true" value="{{ $ads->province }}/{{ $ads->city }}/{{ $ads->area }}" /><s></s>
    </dd>
    <dd>
        详细地址:
        <input style="border: none;width: 65%;" placeholder="请输入详细地址" type="text" name="ads" value="{{ $ads->ads }}">
    </dd>
</dl>

<div style="text-align: center;margin: 5rem;">
        <a style="background: #6495ed;color: #FFF;padding: .5rem 1rem;" href="javascript:;" onclick="addAds({{ $ads->id }})">确认修改地址</a>
    </div>


<script type="text/javascript">
// 地区加载插件
$("#city").click(function (e) {
    SelCity(this,e);
});
$("s").click(function (e) {
    SelCity(document.getElementById("city"),e);
});

// 提交添加地址的表单
function addAds(id) {
    load();
    var name = $('input[name=name]').val();
    var phone = $('input[name=phone]').val();
    var city = $('input[name=city]').val();
    var ads = $('input[name=ads]').val();
    $.post('{{ url('view/ads/edit') }}/'+id, {
        "_token" : '{{ csrf_token() }}',
        "name" : name,
        "phone" : phone,
        "city" : city,
        "ads" : ads
    }, function (ret) {
        var obj = $.parseJSON(ret);
        layer.close(loadBox)
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            location.href = '{{ url('view/ads') }}';
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






































