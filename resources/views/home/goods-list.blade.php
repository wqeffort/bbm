@extends('lib.home.header')
@section('body')
<style type="text/css">
    .data_load {
        text-align: center;
        margin: 1rem 0;
        display: none;
    }
    .load_end {
        text-align: center;
        margin: 1rem 0;
        display: none;
    }
</style>
<div class="search_box" style="background: #666;position: relative;
">
    <div class="search">
        <input style="background: #FFF" type="text" name="search" placeholder="请输入商品名称或者关键字进行搜索！" onchange="search()"><i class="fa fa-search" aria-hidden="true"></i>
        <span>搜索</span>
    </div>
</div>
<div class="goodsListBox">
    <div class="listBox" style="padding: .5rem">
        @if ($goods->isNotEmpty())
            @foreach ($goods as $element)
                <a href="{{ url('goods') }}/{{ $element->id }}">
                    <ul style=" display: flex;justify-content: left;overflow: hidden;border-bottom: 1px solid #EEE;padding: .5rem 0;color: #333;">
                        <li><img style="width: 8rem;" src="{{ url($element->goods_pic) }}" alt="{{ $element->goods_name }}"></li>
                        <li style="margin-left: 1rem;display: flex;flex-direction: column;justify-content: space-between;">
                            <h3 style="white-space: nowrap;overflow: hidden;">{{ $element->goods_name }}</h3>
                            <span style="color:#666;white-space: nowrap;overflow: hidden;">{{ $element->goods_title }}</span>
                            <p><b>{{ $element->goods_point }}</b> 积分</p>
                        </li>
                    </ul>
                </a>
            @endforeach
        @endif
        <p class="data_load"><i class="fa fa-spinner fa-pulse"></i>点击加载更多</p>
    </div>
</div>

{{-- <a href="javascript:;" onclick="location.reload()"><div style="    padding: 1rem;
    background: #C40000;
    color: #FFF;
    text-align: center;">换一批看看</div></a> --}}
<script type="text/javascript">
// 修改默认地址
function adsStatus(id) {
    $.post('{{ url('adsStatus') }}',{
        "_token" : '{{ csrf_token() }}',
        "id" : id
    },function (ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            location.reload();
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }
    })
}
function search() {
    var data = $('input[name=search]').val();
    location.href = '{{ url('goods/list') }}/search/'+data;
}

// 下拉加载更多
// 获取屏幕高度
var page = {{ $page }}
var start = 1
var fn = 1
if (start == 1) {
$(window).scroll(function() {
    // 当前页码
    var h_document = $(document).height();
    var h_window = $(window).height();
    var h_scroll = $(document).scrollTop();
    console.log(h_document)
    console.log(h_window)
    console.log(h_scroll)
    if (h_scroll == (h_document - h_window)) {
        start == 2
        // 获取新的数据
            $.post('{{ url('goods/list/brand') }}/{{ $data }}/'+page,{
                "_token" : '{{ csrf_token() }}'
            },function (ret) {
                var obj = $.parseJSON(ret);
                // console.log(obj);
                $('.data_load').css({
                    "display":"block"
                })
                if (obj.status == 'success') {
                    page++
                    var html = '';
                    var title = '';
                    $.each(obj.data, function(index, val) {
                        if (!val.goods_title) {
                            title=''
                        } else {
                            title = val.goods_title
                        }
                         html += '<a href="{{ url('goods') }}/'+val.id+'"><ul style=" display: flex;justify-content: left;overflow: hidden;border-bottom: 1px solid #EEE;padding: .5rem 0;color: #333;"><li><img style="width: 8rem;" src="{{ url('/') }}/'+val.goods_pic+'" alt="'+val.goods_name+'"></li><li style="margin-left: 1rem;display: flex;flex-direction: column;justify-content: space-between;"><h3 style="white-space: nowrap;overflow: hidden;">'+val.goods_name+'</h3><span style="color:#666;white-space: nowrap;overflow: hidden;">'+title+'</span><p><b>'+val.goods_point+'</b> 积分</p></li></ul></a>'
                    });
                    $('.listBox').append(html);
                    setTimeout(function () {
                        $('.data_load').css({
                            "display":"none"
                        })
                        start == 1
                    }, 1500)
                    console.log(page);
                } else {
                    if (fn == 1) {
                        $('.data_load').css({
                            "display":"none"
                        })
                        $('.listBox').append('<p class="load_end"><i class="fa fa-smile-o"></i> 已经到底了!</p>');
                        $('.load_end').css({
                            "display":"block"
                        })
                        start == 1
                        fn = 2
                    }
                }
            })
        }
})
}
</script>


@endsection






































