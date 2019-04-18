@extends('lib.service.header')
@section('body')
<style type="text/css">
/* latin-ext */
@font-face {
  font-family: 'Gudea';
  font-style: normal;
  font-weight: 400;
  src: local('Gudea'), url(https://fonts-gstatic.proxy.ustclug.org/s/gudea/v7/neIFzCqgsI0mp9CG_oCsNKEyaJQ.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Gudea';
  font-style: normal;
  font-weight: 400;
  src: local('Gudea'), url(https://fonts-gstatic.proxy.ustclug.org/s/gudea/v7/neIFzCqgsI0mp9CI_oCsNKEy.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
/* latin-ext */
@font-face {
  font-family: 'Gudea';
  font-style: normal;
  font-weight: 700;
  src: local('Gudea Bold'), local('Gudea-Bold'), url(https://fonts-gstatic.proxy.ustclug.org/s/gudea/v7/neIIzCqgsI0mp9gz25WPFqwYUp31kXI.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Gudea';
  font-style: normal;
  font-weight: 700;
  src: local('Gudea Bold'), local('Gudea-Bold'), url(https://fonts-gstatic.proxy.ustclug.org/s/gudea/v7/neIIzCqgsI0mp9gz25WBFqwYUp31.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
body {
  -webkit-perspective: 800px;
          perspective: 800px;
  height: 100vh;
  margin: 0;
  overflow: hidden;
  font-family: 'Gudea', sans-serif;
  background: #EA5C54;
  /* Old browsers */
  /* FF3.6+ */
  background: -webkit-gradient(linear, left top, right bottom, color-stop(0%, #EA5C54), color-stop(100%, #bb6dec));
  /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(-45deg, #EA5C54 0%, #bb6dec 100%);
  /* Chrome10+,Safari5.1+ */
  /* Opera 11.10+ */
  /* IE10+ */
  background: -webkit-linear-gradient(315deg, #EA5C54 0%, #bb6dec 100%);
  background: linear-gradient(135deg, #EA5C54 0%, #bb6dec 100%);
  /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#EA5C54 ', endColorstr='#bb6dec',GradientType=1 );
  /* IE6-9 fallback on horizontal gradient */
}
body ::-webkit-input-placeholder {
  color: #4E546D;
}
body .authent {
  display: none;
  background: #35394a;
  /* Old browsers */
  /* FF3.6+ */
  background: -webkit-gradient(linear, left bottom, right top, color-stop(0%, #35394a), color-stop(100%, #1f222e));
  /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(45deg, #35394a 0%, #1f222e 100%);
  /* Chrome10+,Safari5.1+ */
  /* Opera 11.10+ */
  /* IE10+ */
  background: linear-gradient(45deg, #35394a 0%, #1f222e 100%);
  /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#35394a', endColorstr='#1f222e',GradientType=1 );
  /* IE6-9 fallback on horizontal gradient */
  position: absolute;
  left: 0;
  right: 90px;
  margin: auto;
  width: 100px;
  color: white;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-align: center;
  padding: 20px 70px;
  top: 200px;
  bottom: 0;
  height: 70px;
  opacity: 0;
}
body .authent p {
  text-align: center;
  color: white;
}
body .success {
  display: none;
  color: #d5d8e2;
}
body .success p {
  font-size: 14px;
}
body p {
  color: #5B5E6F;
  font-size: 10px;
  text-align: left;
}
body .testtwo {
  left: -320px !important;
}
body .test {
  box-shadow: 0px 20px 30px 3px rgba(0, 0, 0, 0.55);
  pointer-events: none;
  top: -100px !important;
  -webkit-transform: rotateX(70deg) scale(0.8) !important;
          transform: rotateX(70deg) scale(0.8) !important;
  opacity: .6 !important;
  -webkit-filter: blur(1px);
          filter: blur(1px);
}
body .mod_login {
  opacity: 1;
  top: 20px;
  -webkit-transition-timing-function: cubic-bezier(0.68, -0.25, 0.265, 0.85);
  -webkit-transition-property: -webkit-transform,opacity,box-shadow,top,left;
          transition-property: transform,opacity,box-shadow,top,left;
  -webkit-transition-duration: .5s;
          transition-duration: .5s;
  -webkit-transform-origin: 161px 100%;
      -ms-transform-origin: 161px 100%;
          transform-origin: 161px 100%;
  -webkit-transform: rotateX(0deg);
          transform: rotateX(0deg);
  position: relative;
  width: 240px;
  border-top: 2px solid #189F92;
  height: 350px;
  position: absolute;
  left: 0;
  right: 0;
  margin: auto;
  top: 0;
  bottom: 0;
  padding: 50px 40px 40px 40px;
  background: #35394a;
  /* Old browsers */
  /* FF3.6+ */
  background: -webkit-gradient(linear, left bottom, right top, color-stop(0%, #35394a), color-stop(100%, #1f222e));
  /* Chrome,Safari4+ */
  background: -webkit-linear-gradient(45deg, #35394a 0%, #1f222e 100%);
  /* Chrome10+,Safari5.1+ */
  /* Opera 11.10+ */
  /* IE10+ */
  background: linear-gradient(45deg, #35394a 0%, #1f222e 100%);
  /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#35394a', endColorstr='#1f222e',GradientType=1 );
  /* IE6-9 fallback on horizontal gradient */
}
body .mod_login .validation {
  position: absolute;
  z-index: 1;
  right: 10px;
  top: 6px;
  opacity: 0;
}
body .mod_login .disclaimer {
  position: absolute;
  bottom: 20px;
  left: 35px;
  width: 250px;
}
body .mod_login_title {
  color: #afb1be;
  height: 60px;
  text-align: left;
  font-size: 16px;
}
body .mod_login_fields {
  height: 208px;
  position: absolute;
  left: 0;
}
body .mod_login_fields .icon {
  position: absolute;
  z-index: 1;
  left: 36px;
  top: 8px;
  opacity: .5;
}
body .mod_login_fields input[type='password'] {
  color: #189F92 !important;
}
body .mod_login_fields input[type='number'], body .mod_login_fields input[type='password'] {
  color: #afb1be;
  width: 190px;
  margin-top: -2px;
  background: #32364a;
  left: 0;
  padding: 10px 65px;
  border-top: 2px solid #393d52;
  border-bottom: 2px solid #393d52;
  border-right: none;
  border-left: none;
  outline: none;
  font-family: 'Gudea', sans-serif;
  box-shadow: none;
}
body .mod_login_fields__user, body .mod_login_fields__password {
  position: relative;
}
body .mod_login_fields__submit {
  position: relative;
  top: 35px;
  left: 0;
  width: 80%;
  right: 0;
  margin: auto;
}
body .mod_login_fields__submit .forgot {
  float: right;
  font-size: 10px;
  margin-top: 11px;
  text-decoration: underline;
}
body .mod_login_fields__submit .forgot a {
  color: #606479;
}
body .mod_login_fields__submit input {
  border-radius: 50px;
  background: transparent;
  padding: 10px 50px;
  border: 2px solid #189F92;
  color: #189F92;
  text-transform: uppercase;
  font-size: 11px;
  -webkit-transition-property: background,color;
          transition-property: background,color;
  -webkit-transition-duration: .2s;
          transition-duration: .2s;
}
body .mod_login_fields__submit input:focus {
  box-shadow: none;
  outline: none;
}
body .mod_login_fields__submit input:hover {
  color: white;
  background: #189F92;
  cursor: pointer;
  -webkit-transition-property: background,color;
          transition-property: background,color;
  -webkit-transition-duration: .2s;
          transition-duration: .2s;
}

/* Color Schemes */
.love {
  position: absolute;
  right: 20px;
  bottom: 0px;
  font-size: 11px;
  font-weight: normal;
}
.love p {
  color: white;
  font-weight: normal;
  font-family: 'Open Sans', sans-serif;
}
.love a {
  color: white;
  font-weight: 700;
  text-decoration: none;
}
.love img {
  position: relative;
  top: 3px;
  margin: 0px 4px;
  width: 10px;
}

.brand {
  position: absolute;
  left: 20px;
  bottom: 14px;
}
.brand img {
  width: 30px;
}
</style>
<div class='mod_login'>
    <div class="logo_text" style="padding: 1rem 0 2rem 0">
        <img style="width: 100%;" src="{{ url('images/logo-text.png') }}">
    </div>
    <div class='mod_login_title'>
        <span>账号登录</span>
    </div>
    <div class='mod_login_fields'>
        <div class='mod_login_fields__user'>
            <div class='icon'>
                <img src='{{ url('img/user_icon_copy.png') }}'>
            </div>
            <input placeholder='手机号码' name="username" maxlength="10" required="required" type='number'>
            <div class='validation'>
                <img src='{{ url('img/tick.png') }}'>
            </div>
            </input>
        </div>
        <div class='mod_login_fields__password'>
            <div class='icon'>
                <img src='{{ url('img/lock_icon_copy.png') }}'>
            </div>
            <input placeholder='密码' name="password" required="required" type='password'>
            <div class='validation'>
                <img src='{{ url('img/tick.png') }}'>
            </div>
        </div>
        <div class='mod_login_fields__submit'>
            <input type='submit' value='登录'>
            <div class='forgot'>
                {{-- <a href='#'>忘记密码?</a> --}}
            </div>
        </div>
    </div>
    <div class='success'>
        <h2>认证成功</h2>
        <p>正在为您跳转!</p>
    </div>
    <div class='disclaimer'>
            <p>Copyright © 2019 S.J.A.G By Leo</p>
    </div>
</div>
<div class='authent'>
    <img src='{{ url('img/puff.svg') }}'>
    <p>认证中...</p>
</div>
<script type="text/javascript" src='{{ asset('js/stopExecutionOnTimeout.js?t=1') }}'></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script>
function sub() {
    // 获取输入的用户名和密码
        var username = $('input[name=username]').val();
        var password = $('input[name=password]').val();
        console.log(password)
        if (isPhone(username) && password.length > 5) {
            $('.mod_login').addClass('test');
            setTimeout(function () {
                $('.mod_login').addClass('testtwo');
            }, 300);
            setTimeout(function () {
                $('.authent').show().animate({ right: -320 }, {
                    easing: 'easeOutQuint',
                    duration: 600,
                    queue: false
                });
                $('.authent').animate({ opacity: 1 }, {
                    duration: 200,
                    queue: false
                }).addClass('visible');
            }, 500);
            $.post('{{ url('service/login') }}', {
                "_token" : '{{ csrf_token() }}',
                "username" : username,
                "password" : password
            }, function (ret) {
                setTimeout(function () {
                $('.mod_login').removeClass('test');
                $('.mod_login div').fadeOut(123);
            }, 2800);
                setTimeout(function () {
                    $('.authent').show().animate({ right: 90 }, {
                        easing: 'easeOutQuint',
                        duration: 600,
                        queue: false
                    });
                    $('.authent').animate({ opacity: 0 }, {
                        duration: 200,
                        queue: false
                    }).addClass('visible');
                    $('.mod_login').removeClass('testtwo');
                }, 2500);
                var obj = $.parseJSON(ret);
                if (obj.status == 'success') {
                    setTimeout(function () {
                        $('.success').fadeIn();
                        location.href = '{{ url('service') }}'
                    }, 1000);
                }else{
                    location.reload();
                    layer.msg(obj.msg);
                }
            })
        }else{
            layer.msg('用户名或密码格式不正确');
        }
}
$(document).keyup(function(event){
    if(event.keyCode ==13){
        sub();
    }
});
    $('input[type="submit"]').click(function () {
        sub();
    });
    $('input[type="number"],input[type="password"]').focus(function () {
        $(this).prev().animate({ 'opacity': '1' }, 200);
    });
    $('input[type="number"],input[type="password"]').blur(function () {
        $(this).prev().animate({ 'opacity': '.5' }, 200);
    });
    $('input[type="number"],input[type="password"]').keyup(function () {
        if (!$(this).val() == '') {
            $(this).next().animate({
                'opacity': '1',
                'right': '30'
            }, 200);
        } else {
            $(this).next().animate({
                'opacity': '0',
                'right': '20'
            }, 200);
        }
    });
    var open = 0;
    $('.tab').click(function () {
        $(this).fadeOut(200, function () {
            $(this).parent().animate({ 'left': '0' });
        });
    });
</script>
@endsection