<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// 公用接口
Route::group(['middleware' => ['api']], function () {
	Route::any('sms/send', 'Api\IndexController@sms');
    Route::any('sms/send/content', 'Api\IndexController@smsContent');
    // 第一次注册时候验证首次发送短信
    Route::any('sms/send/one', 'Api\IndexController@oneSendSms');
    Route::get('jachat/{phone}', 'Api\IndexController@index');
    Route::post('upload/img', 'Api\IndexController@setUserPic');
    // 接收二进制流图片
    Route::post('upload/image', 'Api\IndexController@uploadFile');
    // 接收二进制流多图上传
    Route::post('upload/image/more', 'Api\IndexController@uploadFileMore');

    // 版本迭代检查
    Route::post('version/info', 'Api\IndexController@version');
});

// 高德定位
Route::group(['middleware' => ['api']], function () {
    // 逆地址解析
    Route::post('geo/reLocation', 'Api\CommonController@reLocation');
    // IP逆地址解析
    Route::post('geo/ipLocation', 'Api\CommonController@ipLocation');
    // 获取天气情况
    Route::post('geo/weather', 'Api\CommonController@weather');
});

// 登录注册接口
Route::group(['middleware' => ['api']], function () {
    Route::post('login','Api\IndexController@login');
    Route::post('register','Api\IndexController@register');
    // 补全用户信息
    Route::post('register/user/info', 'Api\IndexController@setUserInfo');
    // 获取到个人信息
    Route::post('user/info/view', 'Api\IndexController@viewUserInfo');
});



// IM集合
Route::group(['middleware' => ['api']], function () {
    Route::get('chat/getToken', 'Api\Chat\JachatController@getToken'); // 获取Token
    Route::post('chat/user/add', 'Api\Chat\JachatController@addUser');
    // 查询用户UUID
    Route::post('chat/select/user/uuid', 'Api\Chat\JachatController@selectUserUuid');
    // 获取当前用户下的所有好友
    Route::post('chat/get/friend/all', 'Api\Chat\JachatController@friendAll');
    // 添加备注
    Route::post('chat/user/mark/edit', 'Api\Chat\JachatController@mark');
});


// 社区集合
Route::group(['middleware' => ['api']], function () {
    // 发文
    Route::post('community/add', 'Api\Community\CommunityController@add');
    // 第一次获取list数据
    Route::post('community/get/content/now', 'Api\Community\CommunityController@now');
    // 上拉加载更多数据
    Route::post('community/get/content/ago', 'Api\Community\CommunityController@ago');
    // 获取关注的用户
    Route::post('community/follow/list', 'Api\Community\CommunityController@listFollow');
    // 关注用户
    Route::post('community/follow/add', 'Api\Community\CommunityController@addFollow');
    // 取消关注用户
    Route::post('community/follow/cancel', 'Api\Community\CommunityController@cancelFollow');
    // 用户点赞
    Route::post('community/zan/add', 'Api\Community\CommunityController@addZan');
    // 用户取消点赞
    Route::post('community/zan/cancel', 'Api\Community\CommunityController@cancelZan');

    // 添加评论
    Route::post('community/comment/add', 'Api\Community\CommunityController@addComment');
    // 获取评论列表
    Route::post('community/comment/get', 'Api\Community\CommunityController@getComment');
    // 获取子评论信息列表
    Route::post('community/comment/getIdComment', 'Api\Community\CommunityController@getIdComment');
    // 评论 用户点赞
    Route::post('community/comment/zan/add', 'Api\Community\CommunityController@commentAddZan');
    // 评论 用户取消点赞
    Route::post('community/comment/zan/cancel', 'Api\Community\CommunityController@commentCancelZan');
    // 提醒列表  获取好友
    Route::post('community/get/friend/list', 'Api\Chat\JachatController@friendList');

    // 添加屏蔽用户 不看用户发文
    Route::post('community/nolook/add', 'Api\Community\CommunityController@addNoLook');
    // 获取屏蔽的用户
    Route::post('community/nolook/list', 'Api\Community\CommunityController@listNoLook');
    // 取消屏蔽用户
    Route::post('community/nolook/cancel', 'Api\Community\CommunityController@cancelNoLook');
});

// 个人中心信息设置集合
Route::group(['middleware' => ['api']], function() {
    // 设置昵称
    Route::post('user/info/set/setNickname', 'Api\UserController@setNickname');
    // 设置登录密码
    Route::post('user/info/set/setPassword', 'Api\UserController@setPassword');
    // 设置提现密码
    Route::post('user/info/set/setCashPassword', 'Api\UserController@setCashPassword');
    // 设置通讯地址
    Route::post('user/info/set/setAds', 'Api\UserController@setAds');
    // 设置生日
    Route::post('user/info/set/setBirthday', 'Api\UserController@setBirthday');
    // 设置头像
    Route::post('user/info/set/setPic', 'Api\UserController@setPic');

    // 获取用户积分余额
    Route::post('user/info/get/point', 'Api\UserController@getUserPoint');
    // 获取用户积分流水
    // Route::post('user/info/get/log/point', 'Api\UserController@getPointLog');
    // 根据时间获取用户积分流水
    Route::post('user/info/get/log/point/time', 'Api\UserController@getPointLogOnTheTime');
    Route::post('user/info/get/log/point/time/android', 'Api\UserController@getPointLogOnTheTimeForAndroid');
});

// 商城API集合
Route::group(['middleware' => ['api']], function() {
    // 获取商城主页数据
    Route::post('shop/data', 'Api\StoreController@index');
    // 获取商品详情页信息
    Route::post('shop/goods/get', 'Api\Store\GoodsController@goods');

    // 获取购物车商品
    Route::post('shop/cart', 'Api\Store\CartController@index');
    // 添加商品至购物车
    Route::post('shop/cart/add', 'Api\Store\CartController@addCart');
    // 商品移除购物车
    Route::post('shop/cart/del', 'Api\Store\CartController@delCart');
    // 生成订单
    Route::post('shop/order/make', 'Api\Store\OrderController@makeOrder');
    // 显示订单
    Route::post('shop/order/show', 'Api\Store\OrderController@showOrder');
    // 接收处理订单
    Route::post('shop/order/handle', 'Api\Store\OrderController@handleOrder');
    // 订单回调
    Route::post('shop/order/callBack', 'Api\Store\OrderController@callBackOrder');

    // 支付结果查询
    Route::post('shop/order/select', 'Api\Store\OrderController@selectOrder');


    // 获取收货地址
    Route::post('shop/ads', 'Api\Store\AdsController@ads');
    // 获取默认收货地址
    Route::post('shop/ads/in', 'Api\Store\AdsController@adsIn');
    // 添加收货地址
    Route::post('shop/ads/add', 'Api\Store\AdsController@adsAdd');
    // 编辑收货地址
    Route::post('shop/ads/edit', 'Api\Store\AdsController@adsEdit');
    // 显示收货地址
    Route::post('shop/ads/show', 'Api\Store\AdsController@adsShow');
    // 设置默认收货地址
    Route::post('shop/ads/status', 'Api\Store\AdsController@adsStatus');
    // 删除收货地址
    Route::post('shop/ads/del', 'Api\Store\AdsController@adsDel');


    // 获取用户所有订单
    Route::post('shop/order/get/all', 'Api\Store\OrderController@all');
    // 用户收货后确认订单
    Route::post('shop/order/agree', 'Api\Store\OrderController@agree');
    // 物流信息查询
    Route::post('shop/order/express','Api\Store\OrderController@orderExpress');

    // 列表接口
    // 搜索获取商品
    Route::post('shop/goods/list/search', 'Api\Store\GoodsController@search');
    // 根据分类ID获取到分类
    Route::post('shop/goods/list/cate', 'Api\Store\GoodsController@cate');
    // 根据品牌ID获取到品牌
    Route::post('shop/goods/list/brand', 'Api\Store\GoodsController@brand');

    // 获取账户的收藏列表
    Route::post('shop/goods/collection', 'Api\Store\GoodsController@collection');
    Route::post('shop/goods/collection/add', 'Api\Store\GoodsController@collectionAdd');
    Route::post('shop/goods/collection/del', 'Api\Store\GoodsController@collectionDel');


    // 获取文章资讯
    // 获取文章banner
    Route::post('shop/article', 'Api\Store\ArticleController@index');
    Route::post('shop/article/get', 'Api\Store\ArticleController@article');
});


// 推送集合
Route::group(['middleware' => ['api']], function () {
    // 推送
    Route::post('push', 'Api\JPushController@push');

});