@extends('lib.home.header')
@section('body')
<style type="text/css">
    .join_head {
        height: 3rem;
        line-height: 3rem;
        text-align: center;
        color: #FFF;
        background: linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        background: -webkit-linear-gradient(20deg,#27c7fe 0%,#3979ff 50%,#4765f5 100%);
        display: flex;
        justify-content: space-between;
        padding: 0 1rem;
    }
    .join_head h3 {
        width: 50%;
        overflow: hidden;
    }
    .join_head h3 a {
        color: #FFF;
    }
    .join_head a i {
        color: #FFF;
        font-size: 1.5rem;
    }
    .join_head h3 img {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: .5rem;
    }
    .join_head p {
        padding: 2rem 0 1rem 0;
        font-size: 1rem;
        color: #FFF;
    }
    .join_head b {
        display: flex;
        justify-content: space-between;
        /*font-size: 1.5rem;*/
        color: #FFF;
    }
    .user_nav a {
        color: #FFF;
    }
    .join_head b {
        /*font-size: 1.5rem;*/
        color: #FFF;
    }
    .join_cell ul li dl {
        display: flex;
        justify-content: space-between;
        padding: 0 .5rem;
        height: 3rem;
        line-height: 3rem;
        border-bottom: 1px solid #EEE;
    }
    .join_cell ul li dl dd {
        text-align: center;
        overflow: hidden;
    }
    .join_cell ul li dl dd img {
        width: 2rem;
        border-radius: 50%;
        margin-top: .5rem;
    }
    .listData {
        display: flex;
        justify-content: space-between;
        line-height: 2.5rem;
        height: 2.5rem;
        font-size: .8rem;
    }
</style>
<div class="join_head">
    <a href="javascript:;" onclick="javascript:window.history.back(-1);"><i class="fa fa-angle-left"></i></a>
    <h3 style=" margin-left: 1.5rem;"><select id="select" style="    color: #FFF;
    font-size: 1.2rem;
    text-align: center;" onchange="getJoin()">
        <option value="0">会员用户</option>
        <option value="99">准 会 籍</option>
        <option value="1">体验会籍</option>
        <option value="2">男爵会籍</option>
        <option value="3">子爵会籍</option>
        <option value="4">伯爵会籍</option>
        <option value="5">侯爵会籍</option>
        <option value="6">公爵会籍</option>
    </select></h3>
    <b class="count">共 {{ $info->count() }} 人</b>
</div>
<div class="join_cell">
    <ul class="info">
        @if ($user)
            @foreach ($user as $element)
            <li>
                <dl>
                    @if ($element->user_pic)
                        <dd style="width: 10%;"><img src="{{ url($element->user_pic) }}"></dd>
                    @endif
                        <dd style="width: 10%;"><img src="{{ url('images/logo.png') }}"></dd>
                     <dd style="width: 30%;"><span>
                        @if ($element->user_name || $element->user_nickname)
                            @if ($element->user_name)
                                {{ $element->user_name }}
                            @else
                                {{ $element->user_nickname }}
                            @endif
                        @else
                            空
                        @endif
                    </span></dd>
                    <dd style="width: 30%;">{{ $element->rankName }}</dd>
                    <dd style="width: 30%;">{{ $element->user_phone }}</dd>
                </dl>
            </li>
            @endforeach
        @endif
    </ul>
</div>

<script type="text/javascript">
function getJoin() {
    var rank = $("#select option:selected").val();
    var temp = '';
    $.post('{{ url('join/list/tempJoin') }}', {
        "rank" : rank,
        "_token" : '{{ csrf_token() }}'
    }, function(ret) {
        var obj = $.parseJSON(ret);
        if (obj.status == 'success') {
            var html = '';
            console.log(obj.data);
            var count = 0;
            $.each(obj.data, function(index, val) {
                count++;

                if (val.user_pic) {
                    var pic = val.user_pic
                }else{
                    var pic = '{{ url('images/logo.png') }}'
                }
                if (val.user_name) {
                    var name = val.user_name;
                }else{
                    var name = val.user_nickname;
                }
                if (rank == '99') {
                    temp = '<ol class="listData">\
                            <p><a href="javascript:;">赠送总额:'+val.point_open+'</a></p>\
                            <p><a href="javascript:;">剩余积分:'+val.user_point_open+'</a></p>\
                            <p><a href="javascript:;">到期时间:'+val.time+'</a></p>\
                        </ol>'
                }
                html += '<li><dl><dd style="width: 10%;"><img src="'+pic+'"></dd><dd style="width: 30%;"><span>'+name+'</span></dd><dd style="width: 30%;">'+val.rankName+'</dd><dd style="width: 30%;">'+val.user_phone+'</dd></dl>'+temp+'</li>'
            });
            console.log(html);
            $('.info').html(html);
            $('.count').html('共 '+count+' 人')
        }else{
            layer.open({
                content: obj.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
              });
        }
    });
}
</script>
@endsection






































