@extends('lib.home.header')
@section('body')

<style>
a {
    color: #FFF;
}    
body {
    background: #16b77f;
}
.nav {
       text-align: center;
      }
      .nav .tab {
       position: relative;
       overflow: hidden;
       display: flex;
       justify-content: space-between;
       padding: 0 5rem;
        margin-top: 3rem;
}
      .tab a {
       height: 2.56rem;
       line-height:2.56rem;
       display: inline-block;
       /*border-right: 1px solid #e1e1e1;*/
      }
      .tab a:last-child {
       border-right: 0;
      }
    .tab .curr {
       border-bottom: 2px solid #333;
       color: #333;
    }
    .content {
        padding: 2rem;
    }
    .content ul li {
       display: none;
       padding: 5%;
       width: 90%;
       position: relative;
    }
    .logo {
        margin-top: 5rem;
    }
    .logo img {
        width: 10rem;
    }
    </style>
    <div class="nav">
        <div class="logo">
            <img src="{{ url('images/logo.png') }}" alt="log">
        </div>
        <div class="tab border-b">
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" class="curr">账号登录</a>
            <a href="javascript:;" rel="external nofollow" rel="external nofollow" >验证码登录</a>
        </div>
        <div class="content">
            <i class="fa fa-user"></i>
            <input type="text" name="phone" id="phone" value="{{ $phone }}">
            <label for="phone"></label>
            <ul>
                <li class="tet0" style="display: block">
                    <div class="list">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" id="password" value="">
                        <label for="password"></label>
                    </div>
                </li>
                <li class="tet1">
                    <div class="list">
                        <i class="fa fa-lock"></i>
                        <input type="text" style="width:3rem;" name="code" id="code" value="">
                        <label for="code"></label>
                        <a style="background: #FFF;color: #666;padding: .3rem;font-size: .5rem;" onclick="getCode()">发送验证码</a>
                    </div>
                </li>
            </ul>
        </div>
        <div><a style="background: #FFF;color: #16b77f;padding: .5rem 2rem;border-radius: 1rem;font-size: 1rem;" href="javascript:;" onclick="login()">现在登录</a></div>
    </div>
<script type="text/javascript">
$(function() {
    $(".tab a").click(function() {
        $(this).addClass('curr').siblings().removeClass('curr');
        var index = $(this).index();
        number = index;
        $('.nav .content ul li').hide();
        // $('.nav .content ul li:eq(' + number + ')').show();
        if (index == 0) {
            $('.tet0').css('display','block');
        }else if (index == 1) {
            $('.tet1').css('display','block');
        }
    });
})

function getCode() {
    var phone = $('#phone').val();
    load();
    if (isPhone(phone)) {
        $.post("{{ url('sms/send') }}", {
            "_token" : '{{ csrf_token() }}',
            "phone" : phone
        },function (ret) {
            layer.close(loadBox);
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                // location.href = '{{ url('api/shop') }}/'+phone
            } else {
               layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                }); 
            }
        });   
    } else {
        layer.open({
            content: '您输入的电话号码格式不正确!'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }
}

function login() {
    var phone = $('#phone').val();
    var code = $('#code').val();
    var password = $('#password').val();
    load();
    if (isPhone(phone)) {
        $.post('{{ url('api/login') }}',{
            "_token" : '{{ csrf_token() }}',
            "phone" : phone,
            "code" : code,
            "password" : password,
            'token' : 'j0Ikmd6RJxgPCpiYF4BNnsDK5zS9arft78WTXUVelybuvQchEL23oHAOGZqwM1'
        },function (ret) {
            layer.close(loadBox);
            var obj = $.parseJSON(ret);
            if (obj.status == 'success') {
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                location.href = '{{ url('api/shop') }}/'+phone+'?token=j0Ikmd6RJxgPCpiYF4BNnsDK5zS9arft78WTXUVelybuvQchEL23oHAOGZqwM1'
            } else {
                layer.open({
                    content: obj.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });   
            }
        })
    } else {
        
    }
}
</script>
@endsection