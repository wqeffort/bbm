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
        padding: 2rem 0;
        width: 70%;
        margin: 0 auto;
    }
    .content ul li {
       display: none;
       padding: 10% 0;
       /* width: 90%; */
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
            <ol style="margin: 0 auto;padding: 5% 0;    width: 90%;">
                <p><i class="fa fa-user"></i>&nbsp;
                <input type="text" style="width:60%;" name="phone" id="phone" value="@if(isset($phone)){{$phone}}@endif"></p>
                
            </ol>
            <ul style="width: 70%;margin: 0 auto;">
                <li class="tet0" style="display: block">
                    <div class="list">
                        <ol>
                            <p><i class="fa fa-lock"></i>&nbsp;
                            <input type="password" name="password" id="password" value=""></p>
                            
                        </ol>
                    </div>
                </li>
                <li class="tet1">
                    <div class="list">
                        <ol>
                            <p style="display:flex; justify-content: space-between;">
                                <span>
                                    <i class="fa fa-lock"></i>&nbsp;
                                    <input type="text" style="width:3rem;" name="code" id="code" value="">
                                </span>
                                <a style="background: #FFF;color: #666;padding: .3rem;font-size: .5rem;" onclick="getCode()">发送验证码</a>
                            </p>
                        </ol>
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
        $.post('{{ url('jachat/login') }}',{
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
                location.href = '{{ url('jachat/shop') }}/'+phone
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