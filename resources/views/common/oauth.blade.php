<?php
header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID','localhost').'&redirect_uri=http://'.env('HTTP_HOST','localhost').'/oauth&response_type=code&scope=snsapi_userinfo&state=test#wechat_redirect');
?>
