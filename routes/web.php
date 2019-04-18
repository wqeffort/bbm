<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 预加载路由   动作预判,写入SESSION
Route::group(['middleware' => ['oauth']], function () {
    // 接收名片推广
    Route::get('handlePid/{userPid}/{joinPid}','Home\JoinController@handlePid');
    Route::get('handlePid','Home\UserController@handlePid');

    // 增加用户中心的分享页面
    Route::get('user/share/qrcode', 'Home\ShareController@shareQrcode');
    Route::post('user/share/qrcode', 'Home\ShareController@shareQrcodePost');
});


// 活动签到路由
Route::group(['middleware' => ['oauth']], function () {
    Route::post('setLottery', 'Temp\LotteryController@setLottery');
    Route::get('scanBarcode','Temp\LotteryController@scanBarcode');
    Route::any('getBarcode','Temp\LotteryController@getbarcode');
});

// 售票系统路由
Route::group(['middleware' => ['oauth']], function () {
    Route::get('ticket', 'Temp\TicketingController@ticket');
    Route::post('ticket/add', 'Temp\TicketingController@add');
    Route::post('ticket/pay', 'Temp\TicketingController@pay');
    // 验票路由
    Route::get('ticket/validate/{num}', 'Temp\TicketingController@isCard');
    // 接收验票路由!
    Route::post('ticket/validate/{num}', 'Temp\TicketingController@isCardPost');
});



// 页面路由
Route::group(['middleware' => ['oauth']], function () {
    // 根据坐标逆地址解析
    Route::post('getReverseAds', 'Home\IndexController@getReverseAds');
    // 验证用户信息是否已经补全
    Route::post('isUserInfo', 'Home\IndexController@isUserInfo');
    // 补全用户电话信息
    Route::post('addUserInfoPhone', 'Home\IndexController@addUserInfoPhone');
    // 新增补全用户手机页面
    Route::get('addUserInfoPhone', 'Home\IndexController@addUserInfoPhonePage');
});

// 用户中心路由集合
Route::group(['middleware' => ['oauth']], function () {
    // 收藏列表
    Route::get('user/collection', 'Home\UserController@collection');
    // 用户中心主页
    Route::get('user', 'Home\UserController@index');
    // 用户中心信息修改
    Route::get('user/set', 'Home\UserController@setInfo');
    // 用户中心设置提现密码
    Route::get('user/set/cashPassword', 'Home\UserController@cashPasswordPage');
    Route::post('user/set/cashPassword', 'Home\UserController@cashPassword');
    Route::post('verifyCashPassword', 'Home\UserController@verifyCashPassword');

    // 用户中心修改登录密码
    // 发送验证码请求求改
    Route::post('user/set/password', 'Home\UserController@setPassword');
    // 记录新得密码
    Route::post('user/set/password/update', 'Home\UserController@setPasswordUpdate');

    // 用户中心设置地址页面
    Route::get('user/set/ads', 'Home\UserController@setAdsPage');
    Route::post('user/set/ads', 'Home\UserController@setAds');
    // 用户添加收货地址
    Route::get('user/set/ads/add', 'Home\UserController@setAdsAdd');
    // 删除收货地址
    Route::post('user/set/ads/del', 'Home\UserController@setAdsDel');
    // 用户修改收货地址
    Route::get('user/set/ads/edit/{id}', 'Home\UserController@setAdsEdit');
    // 用户中心推广功能
    // 推广主页
    Route::get('extension', 'Home\ExtensionController@info');
    // 接收推广
    Route::get('extension/handle/{uuid}', 'Home\ExtensionController@handle');

    // 分享
    // 分享添加积分,限定每日一次(分享个人,朋友圈)
    Route::post('share/callBack/appMessage', 'Home\ShareController@appMessage');
    Route::post('share/callBack/timeLine', 'Home\ShareController@timeLine');

    // 设置用户身份信息
    Route::get('user/set/uid', 'Home\UserController@uidPage');
    Route::post('user/set/uid', 'Home\UserController@uid');

    // 充值页面
    Route::get('recharge', 'Home\UserController@rechargePage');

    // 开通会员
    Route::get('member', 'Home\UserController@member');
    Route::get('member0', 'Home\UserController@member0');
    Route::get('member1', 'Home\UserController@member1');
    Route::get('member2', 'Home\UserController@member2');
    Route::get('member3', 'Home\UserController@member3');
    Route::get('member4', 'Home\UserController@member4');

    // 债权账户
    Route::get('wallet/bond', 'Home\RtshController@walletBond');
    // 个人账户
    Route::get('wallet/user', 'Home\UserController@walletUser');

    // 用户积分流水
    Route::get('user/point/list','Home\UserController@pointList');
});

// 商城路由集合
Route::group(['middleware' => ['oauth']], function () {
    // 商店主页
    Route::get('shop', 'Home\ShopController@index');
    // 商品详情页面
    Route::get('goods/{goodsId}', 'Home\GoodsController@index');
    // 商品详情页面 测试页面
    Route::get('goods/test/{goodsId}', 'Home\GoodsController@test');
    // 商品列表页面
    Route::get('goods/list/{type}/{data}', 'Home\GoodsController@list');
    Route::post('goods/list/{type}/{data}/{page}', 'Home\GoodsController@listData');
    // 测试路由
    Route::get('test/goods/list/{type}/{data}', 'Home\GoodsController@listTest');
    // 接收属性数组的值进行计算
    Route::post('getArrayAttr', 'Home\GoodsController@getArrayAttr');

    // 添加进入收藏
    Route::post('addCollection', 'Home\GoodsController@addCollection');

    // 添加进购物车
    Route::post('addCar','Home\CarController@addCar');
    // 购物车页面
    Route::get('car', 'Home\CarController@car');
    Route::post('car/total/{goods}', 'Home\CarController@total');
    // 移除购物车
    Route::post('car/setStatus', 'Home\CarController@setStatus');
    // 查询用户地址
    Route::post('car/getUserAds', 'Home\CarController@getUserAds');
    // 购物车页面逆地址解析
    Route::post('car/getLocationAds', 'Home\CarController@getLocationAds');
    // 用户收货地址列表页
    Route::get('adsList', 'Home\CarController@adsList');
    // 获取用户所在地址
    Route::post('adsListGetlocation', 'Home\CarController@adsListGetlocation');
    // 用户添加收货地址
    Route::get('adsAdd', 'Home\CarController@adsAdd');
    // 查询用户积分
    Route::get('getUserPoint', 'Home\UserController@getUserPoint');
    Route::post('getUserRank', 'Home\UserController@getUserRank');
    // 接收用户添加地址的表单
    Route::post('addAds', 'Home\CarController@addAds');
    // 接收用户修改地址表单
    Route::post('editAds', 'Home\CarController@editAds');
    // 用户更换默认收货地址
    Route::post('adsStatus', 'Home\CarController@adsStatus');

    // 用户订单中心
    Route::get('order/list','Home\OrderController@list');

    // 全部订单
    Route::get('order/send/all', 'Home\UserController@allSend');
    // 未发货订单
    Route::get('order/send/no', 'Home\UserController@noSend');
    // 待收货订单
    Route::get('order/send/on', 'Home\UserController@onSend');

    // 购物车添加到订单
    Route::any('order/add', 'Home\OrderController@add');
    // 支付页面路由
    Route::get('car/pay/{goods}', 'Home\OrderController@page');
    // 支付页面补充路由
    Route::get('pay/{num}', 'Home\OrderController@pageAg');
    // 发起积分抵扣
    Route::post('pay/pointPayment/{num}', 'Home\PaymentController@pointPayment');
    // 删除未结算的订单
    Route::post('order/delete/{num}', 'Home\OrderController@delete');

    // 文章资讯
    Route::get('article','Home\ArticleController@index');
    // 获取单条文章内容
    Route::get('article/{id}', 'Home\ArticleController@article');
});


// 微信服务端认证
    Route::any('wechat', 'WechatController@server');
// 微信服务端路由集合
Route::group(['middleware' => ['web','wechat.oauth:snsapi_userinfo']], function () {
    Route::get('lottery', 'Temp\LotteryController@index');
    Route::get('/', 'Home\IndexController@index');
    // 密码登录
    Route::post('login/password', 'Home\UserController@loginPassword');
    // Oauth授权回调
    Route::get('oauth_callback', 'WechatController@callBack');
    // 新增加盟商单页登录
    Route::get('join/login', 'Home\JoinController@joinLogin');
    Route::post('isJoin', 'Home\JoinController@isJoin');
});

// 微信支付API路由集合
Route::group(['middleware' => 'oauth'], function() {
    // 支付路由 处理普通商品订单
    Route::get('payment/{orderNum}', 'WechatPaymentController@shopOrder');
    // 充值路由
    Route::get('recharge/{price}', 'WechatPaymentController@recharge');
    // 会籍购买路由
    Route::get('buyMember/{price}', 'WechatPaymentController@member');
});
Route::group(['middleware' => 'web'], function() {
    // 支付回调路由
    Route::any('paymentCallBack', 'WechatPaymentController@callBack');
});

// 加盟商路由集合
Route::group(['middleware' => 'oauth'], function() {
    // 修改初始密码
    Route::post('join/set/password', 'Home\JoinController@password');
    Route::post('join/set/password/update', 'Home\JoinController@passwordUpdate');
    // 非会员用户
    Route::get('join/list/free', 'Home\JoinController@freeUser');
    // 会员用户
    Route::get('join/list/pay', 'Home\JoinController@payUser');
    // 所有用户
    Route::get('join/list/allFree/{uuid}', 'Home\JoinController@allFreeUser');
    Route::get('join/list/allPay/{uuid}', 'Home\JoinController@allPayUser');
    // 体验会员
    Route::post('join/list/tempJoin', 'Home\JoinController@tempUser');
    // 验证是否已经登录
    Route::post('joinIsLogin', 'Home\JoinController@isLogin');
    // 加盟商代理购买会籍
    Route::get('join/pay', 'Home\JoinController@pay');
    // 查询出预充值会员的信息
    Route::post('join/select/{phone}/{rank}', 'Home\JoinController@select');
    // 接收购买会员订单
    Route::post('join/pay', 'Home\JoinController@handle');
    // 加盟商充值积分
    Route::get('join/recharge', 'Home\JoinController@recharge');
    // 查询出需要充值积分的用户
    Route::post('join/recharge/select/{phone}/{point}', 'Home\JoinController@rechargeSelect');
    // 接收充值积分订单
    Route::post('join/recharge', 'Home\JoinController@handleRecharge');

    // 推广名片页面
    Route::get('join/card', 'Home\JoinController@card');
    // 制作生成名片
    Route::post('join/card/make', 'Home\JoinController@makeCard');

    // 加盟商主页
    Route::get('join', 'Home\JoinController@index');
    // 春蚕主页
    Route::get('spring/asset', 'Home\JoinController@spring');
    Route::get('spring/atm', 'Home\JoinController@springAtm');
    // 订单列表页面
    Route::get('join/order', 'Home\JoinController@order');
    // 加盟商账户流水
    Route::get('join/log','Home\JoinController@log');
    // 加盟商资产管理页面
    Route::get('join/asset', 'Home\JoinController@asset');
    // 加盟商积分账户
    Route::get('join/asset/point', 'Home\JoinController@assetPoint');
    // 加盟商收益账户
    Route::get('join/asset/price', 'Home\JoinController@assetPrice');
    // 获取加盟商账户流水
    Route::post('join/asset/get/log', 'Home\JoinController@getLog');
    // 加盟商返佣积分转现
    Route::post('join/asset/changeCash', 'Home\JoinController@changeCash');
    // 加盟商提现
    Route::get('join/atm', 'Home\JoinController@atm');
    // 查询加盟商不同账户余额
    Route::post('join/getBalance', 'Home\JoinController@getBalance');
    
    Route::get('spring/asset/log', 'Home\JoinController@springLog');
    //加盟商设置页面
    Route::get('join/set', 'Home\JoinController@setInfo');
    // 加盟商注销登录
    Route::get('join/out', 'Home\JoinController@out');
    // 加盟商设置密码
    Route::get('join/set/setPassword', 'Home\JoinController@setPasswordPage');
    Route::post('join/set/setPassword', 'Home\JoinController@setPassword');
    Route::post('join/set/setPasswordUpdate', 'Home\JoinController@setPasswordUpdate');
});

// 融通四海路由集合
Route::group(['middleware' => 'oauth'], function() {
    // 注销登录
    Route::get('loginOut', 'Home\UserController@loginOut');
    // // 密码登录
    // Route::post('login/password', 'Home\UserController@loginPassword');
    // 融通四海主页
    Route::get('rtsh', 'Home\RtshController@index');
    // 项目列表页面
    Route::get('rtsh/object', 'Home\RtshController@object');
    // 获取项目详情
    Route::post('rtsh/object/getDesc', 'Home\RtshController@getDesc');
    // 订单列表
    Route::get('rtsh/order', 'Home\RtshController@order');
    // 融通四海用户中心订单记录
    Route::get('user/rtsh/list', 'Home\RtshController@list');
    // 融通四海订单流水记录
    Route::post('rtsh/order/getLog', 'Home\RtshController@getLog');
    // 融通四海设置续投协议
    Route::post('rtsh/set/protocol', 'Home\RtshController@setProtocol');
});

// 银行卡路由集合
Route::group(['middleware' => 'oauth'], function() {
    // 银行卡列表
    Route::get('bank/list', 'Home\BankController@list');
    // 添加银行卡
    Route::get('bank/create', 'Home\BankController@create');
    // 春蚕添加银行卡
    Route::get('bank/create/spring', 'Home\BankController@createSpring');
    // 春蚕添加银行卡
    Route::post('bank/add/spring', 'Home\BankController@addSpring');
    // 接收添加银行卡数据
    Route::post('bank/add', 'Home\BankController@add');
    // 验证银行卡真实性
    Route::post('isBankCard', 'Home\BankController@isBankCard');
    // 设置默认银行卡
    Route::post('bank/status', 'Home\BankController@status');
    // 删除银行卡
    Route::post('bank/del/{id}', 'Home\BankController@del');
});

// 用户中心卡券路由
Route::group(['middleware' => 'oauth'], function() {
    Route::get('user/card', 'Home\CardController@list');
    Route::post('user/card/add', 'Home\CardController@add');
});

// 用户购买会籍
Route::group(['middleware' => 'oauth'], function() {
    Route::get('pay/rank', 'Home\CardController@list');
});


// 提现路由集合
Route::group(['middleware' => 'oauth'], function() {
    // 提现操作页面
    Route::get('cash', 'Home\CashController@atm');
    // 获取余额
    Route::post('getBalance', 'Home\CashController@getBalance');
    // 接收提现订单
    Route::post('cash/add', 'Home\CashController@add');
    // 账户提现明细
    Route::get('cash/log', 'Home\CashController@log');
    Route::get('join/cash/log', 'Home\CashController@joinLog');
    Route::get('join/spring/cash/log', 'Home\CashController@springLog');
});


















Route::group(['middleware' => ['web']], function () {
    // 后台登录页面
    Route::get('admin/login/{key}', 'Admin\IndexController@login');
    Route::post('admin/loginStatus', 'Admin\IndexController@status');
});
// 后台页面路由集合
Route::group(['middleware' => ['web']], function () {
    // 后台信息页面
    Route::get('admin/info','Admin\IndexController@info');
    // 临时活动 售票
    Route::get('admin/ticket', 'Admin\AdminController@ticket');
























    // 后台主页
    Route::get('admin/', 'Admin\IndexController@index');
    // 导入EXCEL
    Route::get('admin/port/excel', 'OtherController@excel');
    // 后台权限管理
    Route::get('admin/list', 'Admin\AdminController@list');
    // 更改管理员状态
    Route::post('admin/status/{id}', 'Admin\AdminController@status');
    // 删除管理员
    Route::post('admin/del/{id}', 'Admin\AdminController@del');
    // 添加管理员
    Route::get('admin/add', 'Admin\AdminController@add');
    Route::post('admin/add', 'Admin\AdminController@create');
    // 查询出用户
    Route::post('admin/select/getUser', 'Admin\AdminController@getUser');
    // 查询加盟商信息
    Route::post('admin/select/getJoin', 'Admin\AdminController@getJoin');
    // 后台登录页面
    // Route::get('admin/login/{key}', 'Admin\IndexController@login');
    // 登录结果轮询页
    Route::any('admin/loginAuth/{key}', 'Admin\IndexController@loginAuth');
    // 注销登录页面
    Route::post('admin/loginOut', 'Admin\IndexController@loginOut');
    // 用户列表页面
    Route::get('admin/user/list', 'Admin\UserController@list');
    // 用户信息页面
    Route::get('admin/user/info', 'Admin\UserController@info');
    Route::post('admin/user/info', 'Admin\UserController@infoData');
    // 用户充值
    Route::get('admin/user/recharge', 'Admin\UserController@recharge');
    // 广告管理页面
    Route::get('admin/ad/list', 'Admin\AdController@list');
    Route::get('admin/ad/show/{id}', 'Admin\AdController@show');
    Route::post('admin/ad/status/{id}', 'Admin\AdController@status');
    Route::post('admin/ad/edit/{id}', 'Admin\AdController@edit');



    Route::get('admin/join/city', 'Admin\CityController@info');


    // 加盟商列表
    Route::get('admin/join/list', 'Admin\JoinController@list');
    // 添加加盟商
    Route::get('admin/join/add', 'Admin\JoinController@add');
    Route::post('admin/join/add', 'Admin\JoinController@create');
    // 修改加盟商状态
    Route::post('admin/join/status/{id}', 'Admin\JoinController@status');
    // 加盟商充值
    Route::get('admin/join/recharge', 'Admin\JoinController@recharge');
    Route::post('admin/join/recharge', 'Admin\JoinController@addRecharge');
    // 添加加盟商协议 -- 普通协议类
    Route::post('admin/join/protocol', 'Admin\JoinController@protocol');
    // 添加加盟商协议 -- 春蚕协议
    Route::post('amdin/join/spring', 'Admin\JoinController@spring');
    // 查看财务审批得订单列表
    Route::get('admin/join/order', 'Admin\JoinController@order');
    // 加盟商提现列表
    Route::get('admin/join/cash', 'Admin\JoinController@cash');
    // 修改状态提交给财务
    Route::post('admin/join/cash/status/{id}', 'Admin\JoinController@cashSatatus');
    // 取消提现
    Route::post('admin/join/cash/retract', 'Admin\JoinController@retract');

    // 后台搜索
    // 根据姓名进行查询
    Route::post('admin/search/name', 'Admin\SearchController@searchName');
    // 根据手机进行查询
    Route::post('admin/search/phone', 'Admin\SearchController@searchPhone');
    // 根据姓名或者电话号码进行查询
    Route::post('admin/search/user', 'Admin\SearchController@searchUser');
    Route::get('admin/view/user/{uuid}', 'Admin\SearchController@user');
    Route::post('admin/view/user/{uuid}', 'Admin\SearchController@userEdit');
    // 查询用户日志
    Route::get('admin/view/log/{uuid}', 'Admin\SearchController@userLog');
    // 后台修改用户等级
    Route::post('admin/user/payRank', 'Admin\UserController@payRank');
    // 后台为用户充值
    Route::post('admin/user/recharge', 'Admin\UserController@recharge');
    // 查询商城订单
    Route::post('admin/search/order', 'Admin\SearchController@searchOrder');
    // 后台修改银行卡
    Route::post('admin/user/bank/edit/{id}', 'Admin\BankController@edit');
    // 后台删除银行卡
    Route::post('admin/user/bank/del/{id}', 'Admin\BankController@del');
    // 后台修改加盟商状态
    // Route::post('admin/join/status/{id}', 'Admin\JoinController@status');

});

// 商品分类路由集合
Route::group(['middleware' => ['web']], function () {
    Route::get('admin/category/list', 'Admin\CategoryController@list');
    Route::get('admin/category/show/{id}', 'Admin\CategoryController@show');
    Route::get('admin/category/add', 'Admin\CategoryController@add');
    Route::post('admin/category/create', 'Admin\CategoryController@create');
    Route::post('admin/category/edit/{id}', 'Admin\CategoryController@edit');
    Route::post('admin/category/status/{id}', 'Admin\CategoryController@status');
});

// 商品品牌路由集合
Route::group(['middleware' => ['web']], function () {
    Route::get('admin/brand/list', 'Admin\BrandController@list');
    Route::get('admin/brand/show/{id}', 'Admin\BrandController@show');
    Route::get('admin/brand/add', 'Admin\BrandController@add');
    Route::post('admin/brand/create', 'Admin\BrandController@create');
    Route::post('admin/brand/edit/{id}', 'Admin\BrandController@edit');
    Route::post('admin/brand/status/{id}', 'Admin\BrandController@status');

});

// 商品属性路由集合
Route::group(['middleware' => ['web']], function () {
    Route::get('admin/attr/list', 'Admin\AttributeController@list');
    Route::get('admin/attr/show/{id}', 'Admin\AttributeController@show');
    Route::get('admin/attr/add', 'Admin\AttributeController@add');
    Route::post('admin/attr/create', 'Admin\AttributeController@create');
    Route::post('admin/attr/edit/{id}', 'Admin\AttributeController@edit');
    Route::post('admin/attr/status/{id}', 'Admin\AttributeController@status');
    // 模糊查询商品信息
    Route::post('admin/attr/getGoods/{id}', 'Admin\AttributeController@getGoods');

});

// 商品管理路由集合
Route::group(['middleware' => ['web']], function () {
    Route::get('admin/goods/list', 'Admin\GoodsController@list');
    Route::get('admin/goods/show/{id}', 'Admin\GoodsController@show');
    Route::get('admin/goods/add', 'Admin\GoodsController@add');
    Route::post('admin/goods/create', 'Admin\GoodsController@create');
    Route::post('admin/goods/edit/{id}', 'Admin\GoodsController@edit');
    Route::post('admin/goods/status/{id}', 'Admin\GoodsController@status');
    Route::post('admin/goods/isNew/{id}', 'Admin\GoodsController@isNew');
    Route::post('admin/goods/isHot/{id}', 'Admin\GoodsController@isHot');
});

// 文章管理路由集合
Route::group(['middleware' => ['web']], function () {
    Route::get('admin/article/list', 'Admin\ArticleController@list');
    Route::get('admin/article/add', 'Admin\ArticleController@add');
    Route::get('admin/article/show/{id}', 'Admin\ArticleController@show');
    Route::post('admin/article/edit/{id}', 'Admin\ArticleController@edit');
    Route::post('admin/article/create', 'Admin\ArticleController@create');
    Route::post('admin/article/status/{id}', 'Admin\ArticleController@status');
});
// 订单管理路由集合
Route::group(['middleware' => ['web']], function () {
    Route::get('admin/order/info', 'Admin\OrderController@info');
    Route::post('admin/order/info', 'Admin\OrderController@infoData');
    Route::get('admin/order/list', 'Admin\OrderController@list');
    Route::post('admin/order/getOrderInfo', 'Admin\OrderController@getOrderInfo');
    Route::get('admin/order/printGoodsList/{orderNum}', 'Admin\OrderController@printGoodsList');
    // 未发货订单列表
    Route::get('admin/order/no', 'Admin\OrderController@no');
    // 已发货订单列表
    Route::get('admin/order/on', 'Admin\OrderController@on');
    // 搜索订单
    Route::post('admin/order/search', 'Admin\OrderController@searchOrder');
    // 发货
    Route::post('admin/order/send/{num}', 'Admin\OrderController@send');
    // 帮助用户下单
    Route::get('admin/order/help', 'Admin\OrderController@help');
    Route::post('admin/order/help', 'Admin\OrderController@helpData');
    // 查询商品
    Route::post('admin/order/help/select', 'Admin\OrderController@select');
    Route::post('admin/order/help/select/{goodsId}','Admin\OrderController@goodsInfo');
    Route::post('admin/order/mark/edit','Admin\OrderController@editMark');
    Route::post('admin/order/help/code', 'Admin\OrderController@code');
    Route::post('admin/order/get/user','Admin\OrderController@getUser');
    // 移除用户订单
    Route::post('admin/order/remove', 'Admin\OrderController@remove');
});


// 财务管理集合
Route::group(['middleware' => ['web']], function () {
    Route::get('admin/finance/list', 'Admin\FinanceController@list');
    // 查询出订单
    Route::get('admin/finance/show/{id}', 'Admin\FinanceController@show');
    // 处理订单
    Route::post('admin/finance/{id}/edit', 'Admin\FinanceController@edit');
    // 提现订单
    Route::get('admin/finance/cash', 'Admin\FinanceController@cash');
    Route::get('admin/finance/cash/{id}', 'Admin\FinanceController@showCash');
    Route::post('admin/finance/{id}/cash', 'Admin\FinanceController@handleCash');
    Route::get('admin/finance/cashAll', 'Admin\FinanceController@cashAll');



    // 私账财务
    Route::get('admin/finance/foreign/cash', 'Admin\FinanceController@foreignCash');
    Route::get('admin/finance/foreign/cash/all', 'Admin\FinanceController@foreignCashAll');
});



// 顺丰API路由集合
Route::group(['middleware' => ['web']], function () {
    // 令牌回调接口 接收顺丰回调Access_Token
    Route::any('express/callBack/receiveAccessToken', 'ExpressController@receiveToken');

    // 快速下单结果回调接收地址
    Route::any('express/callBack/receiveResult', 'ExpressController@receiveResult');
    // 获取令牌
    Route::any('express/getAccessToken', 'ExpressController@getAccessToken');
    // 后台快速下单
    Route::any('express/getExpressInfo', 'ExpressController@getExpressInfo');
    // 下单后查询订单编号
    Route::any('express/orderQuery/{orderNum}', 'ExpressController@orderQuery');
    // 获取运单图片
    Route::any('express/getExpressImg/{orderNum}', 'ExpressController@getExpressImg');
    // 获取物流信息
    Route::post('express/info/{orderNum}', 'ExpressController@info');
    // 订单快捷发货
    Route::post('order/express/swift', 'ExpressController@swift');
});


// 融通四海路由集合
Route::group(['middleware' => ['web']], function () {
    // 信息统计页面
    Route::get('admin/rtsh/info', 'Admin\RtshController@info');
    // 项目列表
    Route::get('admin/rtsh/list', 'Admin\RtshController@list');
    // 发布项目
    Route::get('admin/rtsh/create', 'Admin\RtshController@createObj');
    // 接收发布的项目
    Route::post('admin/rtsh/add', 'Admin\RtshController@addObj');
    // 显示单个项目
    Route::get('admin/rtsh/show/{id}', 'Admin\RtshController@objShow');
    // 编辑单个项目
    Route::post('admin/rtsh/edit/{id}', 'Admin\RtshController@objEdit');
    // 订单录入
    Route::get('admin/rtsh/order/create', 'Admin\RtshController@orderCreate');
    // 接收订单录入
    Route::post('admin/rtsh/order/add', 'Admin\RtshController@orderAdd');
    // 订单列表
    Route::get('admin/rtsh/order/list', 'Admin\RtshController@orderList');
    Route::get('admin/rtsh/order/list/all', 'Admin\RtshController@orderListAll');
    // 查询出用户的信息和
    Route::post('admin/rtsh/select/user', 'Admin\RtshController@selectUser');
    // 查询出加盟商信息
    Route::post('admin/rtsh/select/join', 'Admin\RtshController@selectJoin');
    // 查询获取用项目的信息
    Route::post('admin/rtsh/select/obj', 'Admin\RtshController@selectObj');
    // 处理订单完结
    Route::post('admin/rtsh/order/handle', 'Admin\RtshController@handleEnd');
    // 订单派息
    Route::post('admin/rtsh/order/refund', 'Admin\RtshController@refundEnd');
    // 处理订单派息
    Route::get('admin/rtsh/order/refund', 'Admin\RtshController@refund');
    Route::get('admin/rtsh/order/refund/all', 'Admin\RtshController@refundAll');

    // 添加续期订单
    Route::get('admin/rtsh/order/renew', 'Admin\RtshController@renew');
    Route::post('admin/rtsh/order/renew', 'Admin\RtshController@renewAdd');
    // 查看订单
    Route::get('admin/rtsh/order/view/{num}', 'Admin\RtshController@viewOrder');
    Route::post('admin/rtsh/order/view/{num}', 'Admin\RtshController@viewPost');

    // 融通四海提现列表
    Route::get('admin/rtsh/cash', 'Admin\RtshController@cash');
    // 搜索提现
    Route::post('admin/rtsh/search/cash/{name}', 'Admin\RtshController@searchCash');
    // 修改状态提交给财务
    Route::post('admin/rtsh/cash/status/{id}', 'Admin\RtshController@cashSatatus');
    // 账户调度
    Route::get('admin/rtsh/account', 'Admin\RtshController@account');
    // 查询账户
    Route::post('admin/rtsh/get/account', 'Admin\RtshController@getAccount');
    // 接收账户调度
    Route::post('admin/rtsh/account/action', 'Admin\RtshController@actionAccount');

    // 账户详细信息查询
    Route::get('admin/rtsh/user/{uuid}', 'Admin\RtshController@userInfo');
    Route::post('admin/rtsh/user/{uuid}', 'Admin\RtshController@userInfoEdit');

    // 加盟商提成列表
    Route::get('admin/rtsh/order/join/refund', 'Admin\RtshController@joinRefund');
    Route::post('admin/rtsh/order/join/refund', 'Admin\RtshController@joinRefundPost');

    // 融通四海订单搜索
    Route::post('admin/rtsh/search/order/{num}', 'Admin\RtshController@searchOrder');

    Route::post('admin/rtsh/search/refund/order/{name}', 'Admin\RtshController@searchRefund');

    Route::post('admin/rtsh/search/join/refund/{name}', 'Admin\RtshController@searchJoin');
});



// 测试导入数据路由
// Route::group(['middleware' => ['web']], function () {
//     // 第一步,赋予UUID
//     Route::get('test/uuid', 'OtherController@testUuid');
//     // 第二部,赋予加盟商UUID
//     Route::get('test/join/uuid', 'OtherController@joinUuid');
//     // 赋予钱包UUID
//     Route::get('test/wallet', 'OtherController@testWallet');
//     // 添加加盟商进表
//     Route::get('test/join', 'OtherController@testJoin');
//     // 添加用户进表
//     Route::get('test/user', 'OtherController@testUser');
//     // 到处银行卡信息
//     Route::get('test/bank', 'OtherController@testBank');
//     // 获取到融通四海的订单
//     Route::get('test/rtsh/order', 'OtherController@testRtshOrder');
// });

// 合伙人
Route::group(['middleware' => ['web']], function () {
    Route::get('sale/transfer', 'Home\SaleController@transfer');
    // 查询出需要充值积分的用户
    Route::post('sale/select/recharge/{phone}/{point}', 'Home\SaleController@selectSaleRecharge');
    // 接收充值积分
    Route::post('sale/recharge', 'Home\SaleController@handleRecharge');
    // 添加合伙人页面
    Route::get('sale/add', 'Home\SaleController@pageAdd');
    Route::post('sale/add', 'Home\SaleController@addSale');
    // 添加合伙人查询
    Route::post('sale/add/select/{phone}', 'Home\SaleController@selectAdd');
    // 获取合伙人发展的用户数量
    Route::post('sale/get/user/count', 'Home\SaleController@userCount');
    Route::post('sale/get/user/count/top', 'Home\SaleController@userCountTop');
    Route::post('sale/get/sale/count', 'Home\SaleController@saleCount');
    Route::get('sale/pid/saleList/{uuid}', 'Home\SaleController@saleList');
    // 增加开拓积分
    Route::get('sale/open', 'Home\SaleController@openPage');
    // 查询非会员用户
    Route::post('sale/add/select/open/{phone}', 'Home\SaleController@selectOpenUser');
    // 为非会员用户进行充值
    Route::post('sale/open/recharge', 'Home\SaleController@openRecharge');
    // 开拓积分转换
    Route::post('sale/change/open', 'Home\SaleController@openChange');
    // 开拓积分用户列表
    Route::get('sale/open/list/{uuid}', 'Home\SaleController@openList');
});




// 公共路由
Route::group(['middleware' => ['web']], function () {
    // 生成验证码图片
    Route::any('makeCode', 'OtherController@makeCode');
    // 验证验证码
    Route::any('isCode', 'OtherController@isCode');
    // 发送短信验证码
    Route::post('sms/send', 'OtherController@send');
    // 新的短信接口,带返回信息
    Route::post('sendsmsPost', 'Home\IndexController@sendsmsCode');
    // 发送变量短信
    Route::any('sms/send/var', 'OtherController@varSms');
    // 上传图片接口
    Route::post('uploadImage', 'OtherController@img');
    Route::post('imgBase64', 'OtherController@imgBase64');
    // 阿里云图片识别接口
    Route::post('ocr', 'OtherController@ocr');

    // 测试接口
    Route::get('test', 'OtherController@test');
    // 后台搜索功能
    Route::any('admin/search', 'Admin\SearchController@search');

});
Route::group(['middleware' => ['web']], function () {
    // 春蚕提现报表
    Route::get('admin/table/spring/cash', 'Admin\TableController@springCash');
    Route::post('admin/table/spring/cash/find', 'Admin\TableController@springCashFind');
    Route::get('admin/table/spring/cash/print/{time}', 'Admin\TableController@springCashPrint');

    // 加盟商提现报表
    Route::get('admin/table/join/cash', 'Admin\TableController@joinCash');
    Route::post('admin/table/join/cash/find', 'Admin\TableController@joinCashFind');
    Route::get('admin/table/join/cash/print/{time}', 'Admin\TableController@joinCashPrint');
    // 财务报表导出
    Route::get('admin/table/finance', 'Admin\TableController@finance');
    Route::post('admin/table/finance/find', 'Admin\TableController@financeFind');
    Route::get('admin/table/finance/print/{time}', 'Admin\TableController@financePrint');
});



// jachat路由
Route::group(['middleware' => ['web']], function () {
    Route::post('/jachat/login','Home\JachatController@login');
    Route::get('/jachat/shop/{phone}', 'Home\JachatController@index');
});

















// 新后台测试路由
Route::group(['middleware' => ['web']], function () {
    // 登录页面
    Route::get('service/login','Service\IndexController@login');
    Route::post('service/login','Service\IndexController@loginPost');
});


// 后台验证权限集合
Route::group(['middleware' => ['web','admin']], function () {
    // 后台主框架页
    Route::get('service', 'Service\IndexController@index');
    // 后台主页 信息页
    Route::get('service/info', 'Service\IndexController@info');
});


// 管理员集合
Route::group(['middleware' => ['web','admin']], function () {
    // 管理员列表
    Route::get('service/admin', 'Service\AdminController@index');
    // 修改管理员状态
    Route::post('service/admin/status/{id}', 'Service\AdminController@status');
    // 添加管理员
    Route::get('service/admin/add', 'Service\AdminController@add');
    Route::post('service/admin/add', 'Service\AdminController@addPost');
});

// 后台人员管理模块
Route::group(['middleware' => ['web','admin']], function () {
    // 用户列表页面
    Route::get('service/user', 'Service\UserController@index');

    // 信息界面
    // 用户详细信息
    Route::get('service/user/info/{uuid}', 'Service\UserController@info');
    // 用户基本信息修改
    Route::post('service/user/info/edit', 'Service\UserController@infoPost');
    // 用户地址信息修改
    Route::post('service/user/ads/edit', 'Service\UserController@infoPostAds');
    // 用户修改绑定关系
    Route::post('service/user/userPid/edit', 'Service\UserController@infoPostUserPid');
    Route::post('service/user/joinPid/edit', 'Service\UserController@infoPostJoinPid');

    // 充值界面
    // 用户充值及会员开通信息
    Route::get('service/user/pay/{uuid}', 'Service\UserController@pay');

    // 修改会籍
    Route::post('service/user/rank/change', 'Service\UserController@changeRank');
    // 修改积分
    Route::post('service/user/point/change', 'Service\UserController@changePoint');


    // 用户日志界面
    Route::get('service/user/log/{uuid}', 'Service\UserController@pointLog');
    // 获取用户积分
    Route::post('service/user/get/userPoint/log/{uuid}', 'Service\UserController@userPointLog');
    // 获取用户积分
    Route::post('service/user/log/get/point/{uuid}', 'Service\LogController@userPoint');
    // 获取用户订单
    Route::post('service/user/log/get/order/{uuid}', 'Service\LogController@userOrder');
    // 获取加盟商积分流水
    Route::post('service/join/log/get/point/{uuid}', 'Service\LogController@joinPoint');
    // 获取春蚕流水
    Route::post('service/join/log/get/spring/{uuid}', 'Service\LogController@joinSpring');


    // 加盟商管理
    // 获取加盟商列表
    Route::get('service/join', 'Service\JoinController@index');
});

// 搜索模块
Route::group(['middleware' => ['web','admin']], function () {
    // 用户列表页面
    Route::post('service/search/user', 'Service\SearchController@userInfo');
});

Route::group(['middleware' => ['web','admin']], function () {
    // 迭代管理
    Route::get('service/app/info', 'Service\SetController@infoList');
    // 轮播设置
    Route::get('service/app/banner', 'Service\SetController@bannerList');
    Route::get('service/app/banner/add', 'Service\SetController@bannerAdd');
    Route::post('service/app/banner/add', 'Service\SetController@bannerAddPost');
    Route::post('service/app/banner/status/{id}', 'Service\SetController@bannerStatus');
    Route::get('service/app/banner/edit/{id}', 'Service\SetController@bannerEdit');
    Route::post('service/app/banner/edit/{id}', 'Service\SetController@bannerEditPost');
    // 广告设置
    Route::get('service/app/ad', 'Service\SetController@adList');
    Route::get('service/app/ad/add', 'Service\SetController@adAdd');
    Route::post('service/app/ad/add', 'Service\SetController@adAddPost');
    Route::post('service/app/ad/status/{id}', 'Service\SetController@adStatus');
    Route::get('service/app/ad/edit/{id}', 'Service\SetController@adEdit');
    Route::post('service/app/ad/edit/{id}', 'Service\SetController@adEditPost');
    // 推送管理
    Route::get('service/app/push', 'Service\SetController@pushList');
});

// oss图片上传
Route::group(['middleware' => ['web','admin']], function () {
    // 用户列表页面
    Route::post('service/img', 'Service\IndexController@img');
});











// webview路由
Route::group(['middleware' => ['webview']], function () {
    // 主页
    Route::get('view/', 'Web\IndexController@index');
    Route::get('webView/{uuid}', 'Web\IndexController@webIndex');
    // 页面数据获取
    Route::get('viewData/banner', 'Web\IndexController@banner');

    // 文章资讯
    Route::get('view/article/cate/{id}', 'Web\ArticleController@cate');
    Route::get('view/article','Web\ArticleController@index');
    // 获取单条文章内容
    Route::get('view/article/{id}', 'Web\ArticleController@article');


    // 分类页面
    Route::get('view/cate', 'Web\CategoryController@index');

    // 商品详情页面
    Route::get('view/goods/{id}', 'Web\GoodsController@index');
    // 获取属性列表
    Route::post('view/goods/get/attr/{id}', 'Web\GoodsController@getAttr');
    // 商品列表
    Route::get('view/goods/list/{type}/{data}', 'Web\GoodsController@list');
    Route::post('view/goods/list/{type}/{data}/{page}', 'Web\GoodsController@listData');


    // 购物车
    Route::get('view/cart', 'Web\GoodsController@cart');
    // 加入购物车!
    Route::post('view/cart/addCart/{goodsId}', 'Web\GoodsController@addCart');
    // 移除购物车
    Route::post('view/cart/remove/{cartId}', 'Web\GoodsController@removeCart');


    // 订单
    // 生成订单
    Route::post('view/order/make', 'Web\OrderController@makeOrder');

    // 订单确认页面
    Route::get('view/order/set/{orderNum}', 'Web\OrderController@setOrder');
    // 查看订单页面
    Route::get('view/order/info/{orderNum}', 'Web\OrderController@viewOrder');
    // 添加订单备注
    Route::post('view/order/edit/ads', 'Web\OrderController@editAds');

    // 地址
    // 地址列表
    Route::get('view/ads', 'Web\UserController@ads');
    // Route::post('view/ads', 'Web\UserController@adsPost');
    // 检查地址
    Route::post('view/ads/select', 'Web\UserController@adsSelect');

    // 添加地址
    Route::get('view/ads/add', 'Web\UserController@adsAdd');
    Route::post('view/ads/add', 'Web\UserController@adsAddPost');

    // 编辑地址
    Route::get('view/ads/edit/{id}', 'Web\UserController@adsEdit');
    Route::post('view/ads/edit/{id}', 'Web\UserController@adsEditPost');
    // 删除地址
    Route::post('view/ads/del', 'Web\UserController@adsDel');
    // 设置默认地址
    Route::post('view/ads/status', 'Web\UserController@adsStatus');




    // 个人中心
    // 用户中心首页
    Route::get('view/user', 'Web\UserController@index');

    // 积分
    Route::get('view/point', 'Web\UserController@point');
    // 获取用户积分日志
    Route::post('view/point/get/log', 'Web\UserController@pointLog');
    // 收藏
    Route::get('view/collection', 'Web\UserController@collection');
    // 卡券
    Route::get('view/card', 'Web\CardController@list');
    Route::post('view/card/add', 'Web\CardController@add');
    // 订单
    Route::get('view/order', 'Web\OrderController@index');

    // 支付接口集合
    // 微信支付
    Route::post('view/payment/wechat', 'Web\OrderController@wechat');

    // 积分支付
    Route::post('view/payment/point', 'Web\OrderController@point');


});
// 微信支付 notify回调
    Route::any('notify/wechat', 'PaymentController@wechatNotify');

