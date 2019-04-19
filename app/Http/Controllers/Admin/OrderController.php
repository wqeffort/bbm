<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
// 加载Model
use App\Model\User;
use App\Model\order;
use App\Model\goods;
use App\Model\attribute;
use App\Model\log_point_user;
use Log;
use App\Model\ads;

class OrderController extends Controller
{
    public function info()
    {
        $input = Input::all();
        $start = '2018-07-19 00:00:00';
        $end = date('Y-m-d H:i:s', time());
        $orderAll = order::where('order.status', '1')
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->whereBetween('order.created_at', [$start, $end])
            ->get();
        $order = order::orderBy('order.id', 'ASC')
            ->where('order.status', '1')
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->whereBetween('order.created_at', [$start, $end])
            // ->groupBy('order.num')
            ->get();
        // dd($order);
        // dd($order->sum('point'));
        $pointAll = order::where('order.status', '1')
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->whereBetween('order.created_at', [$start, $end])
            ->where('type', '2')
            ->get()->sum('point');
        $priceAll = order::where('order.status', '1')
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->whereBetween('order.created_at', [$start, $end])
            ->where('type', '1')
            ->get()->sum('price');
        $data['goods'] = order::orderBy('order.id', 'ASC')
            ->where('order.status', '1')
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->whereBetween('order.created_at', [$start, $end])
            // ->groupBy('order.goods_id')
            ->get();
        foreach ($data['goods'] as $key => $value) {
            $value['count'] = order::where('goods_id', $value->goods_id)
                ->where('status', '1')
                ->whereBetween('order.created_at', [$start, $end])
                ->get()->sum('goods_num');
            $goods[] = $value;
        }
        // dd($goods);
        return view('admin.order-info', compact('orderAll', 'order', 'pointAll', 'priceAll'));
        // dd($newOrder);
    }

    public function infoData()
    {
        $input = Input::all();
        if ($input['day'] == 'more') {
            $data['orderAll'] = order::where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->whereBetween('order.created_at', [$input['start'], $input['end']])
                ->get();
            $data['order'] = order::orderBy('order.id', 'ASC')
                ->where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->whereBetween('order.created_at', [$input['start'], $input['end']])
                ->groupBy('order.num')
                ->get()->count();
            // dd($order);
            // dd($order->sum('point'));
            $data['totalNum'] = $data['orderAll']->sum('goods_num');
            $data['total'] = $data['orderAll']->sum('point');
            $data['pointAll'] = order::where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->whereBetween('order.created_at', [$input['start'], $input['end']])
                ->where('type', '2')
                ->get()->sum('point');
            $data['priceAll'] = order::where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->whereBetween('order.created_at', [$input['start'], $input['end']])
                ->where('type', '1')
                ->get()->sum('price');
            // 获取商品的销售详情
            $goods = order::orderBy('order.id', 'ASC')
                ->where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->whereBetween('order.created_at', [$input['start'], $input['end']])
                ->groupBy('order.goods_id')
                ->select('order.*', 'goods.goods_name', 'goods.goods_pic', 'goods.goods_price', 'goods.goods_point')
                ->get();
            foreach ($goods as $key => $value) {
                $info = order::where('goods_id', $value->goods_id)
                    ->where('status', '1')
                    ->whereBetween('order.created_at', [$input['start'], $input['end']])
                    ->get();
                $value['count'] = $info->sum('goods_num');
                $value['count_price'] = $info->sum('price');
                $value['count_point'] = $info->sum('point');
                $data['goods'][] = $value;
            }
        } else {
            $data['orderAll'] = order::where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->where('order.created_at', 'like', $input['start'].'%')
                ->get();
            // dd($data['orderAll']);
            $data['order'] = order::orderBy('order.id', 'ASC')
                ->where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->where('order.created_at', 'like', $input['start'].'%')
                ->groupBy('order.num')
                ->get()->count();
            // dd($order);
            // dd($order->sum('point'));
            $data['totalNum'] = $data['orderAll']->sum('goods_num');
            $data['total'] = $data['orderAll']->sum('point');
            $data['pointAll'] = order::where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->where('order.created_at', 'like', $input['start'].'%')
                ->where('type', '2')
                ->get()->sum('point');
            $data['priceAll'] = order::where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->where('order.created_at', 'like', $input['start'].'%')
                ->where('type', '1')
                ->get()->sum('price');
            // 获取商品的销售详情
            $goods = order::orderBy('order.id', 'ASC')
                ->where('order.status', '1')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->where('order.created_at', 'like', $input['start'].'%')
                ->select('order.*', 'goods.goods_name', 'goods.goods_pic', 'goods.goods_price', 'goods.goods_point')
                ->groupBy('order.goods_id')
                ->get();
            foreach ($goods as $key => $value) {
                $value['count'] = order::where('goods_id', $value->goods_id)
                    ->where('status', '1')
                    ->where('order.created_at', 'like', $input['start'].'%')
                    ->get()->sum('goods_num');
                $data['goods'][] = $value;
            }
        }
        // dd($data['goods']);
        $result = $this->result('success', '查询成功!', $data);

        return $result;
    }

    // 获取到所有订单的列表
    public function list()
    {
        // 获取用户的所有订单
        $order = order::orderBy('order.id', 'DESC')
                ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
                ->leftJoin('ads', 'ads.id', '=', 'order.ads')
                ->select('user.user_nickname', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads', 'ads.name', 'ads.phone', 'order.*')
                ->paginate(50);
        // ->groupBy('num');
        $orderInfo = $order->unique('num');
        foreach ($orderInfo as $key => $value) {
            // dd($value);
            $attr = array();
            foreach (explode(',', $value->goods_attr) as $a => $b) {
                $attr[] = attribute::find($b)->attr_name;
            }
            $value->attr = $attr;
            $data[] = $value;
        }
        // dd($data);
        return view('admin.order-list', compact('data', 'order'));
    }

    // 根据单号获取订单详细信息
    public function getOrderInfo()
    {
        $input = Input::all();
        // dd($input);
        $data = order::where('order.num', $input['num'])
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
            ->leftJoin('ads', 'ads.id', '=', 'order.ads')
            ->select('user.user_nickname', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads as adds', 'ads.name', 'ads.phone', 'order.*')
            ->get();
        $order['totalPrice'] = 0;
        $order['totalPoint'] = 0;
        $order['totalNum'] = 0;
        $order['ads'] = '';
        $order['name'] = '';
        $order['phone'] = '';
        $order['express'] = '';
        $order['express_img'] = '';
        $order['express_status'] = '';
        $order['express_type'] = '';
        $order['num'] = '';
        $order['mark'] = '';
        // dd($data);
        foreach ($data as $key => $value) {
            $order['totalPrice'] += $value->price;
            $order['totalPoint'] += $value->point;
            $order['totalNum'] += $value->goods_num;
            $order['ads'] = $value->province.$value->city.$value->area.$value->adds;
            $order['num'] = $value->num;
            $order['mark'] = $value->mark;
            $order['name'] = $value->name;
            $order['phone'] = $value->phone;
            $order['express'] = explode(',', $value->express);
            $order['express_img'] = explode(',', $value->express_img);
            $order['express_status'] = $value->express_status;
            $order['express_type'] = $value->express_type;
            $attr = array();
            foreach (explode(',', $value->goods_attr) as $a => $b) {
                $attr[] = attribute::find($b)->attr_name;
            }
            $value->attr = $attr;
            $newData[] = $value;
        }
        $order['data'] = $newData;
        // dd($order);
        $result = $this->result('success', '成功', $order);

        return $result;
    }

    // 打印装箱清单
    public function printGoodsList($orderNum)
    {
        // dd($orderNum);
        $data = order::where('order.num', $orderNum)
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
            ->leftJoin('ads', 'ads.id', '=', 'order.ads')
            ->select('user.user_nickname', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads as aads', 'ads.name', 'ads.phone', 'order.*')
            ->get();
        $order['totalPrice'] = 0;
        $order['totalPoint'] = 0;
        $order['totalNum'] = 0;
        $order['ads'] = '';
        $order['name'] = '';
        $order['phone'] = '';
        $order['express'] = '';
        $order['express_status'] = '';
        $order['express_type'] = '';
        $order['num'] = '';
        $order['type'] = '';
        $order['mark'] = '';
        // dd($data);
        foreach ($data as $key => $value) {
            $order['totalPrice'] += $value->price;
            $order['totalPoint'] += $value->point;
            $order['totalNum'] += $value->goods_num;
            $order['ads'] = $value->province.$value->city.$value->area.$value->aads;
            $order['type'] = $value->type;
            $order['mark'] = $value->mark;
            $order['num'] = $value->num;
            $order['name'] = $value->name;
            $order['phone'] = $value->phone;
            $order['express'] = $value->express;
            $order['express_status'] = $value->express_status;
            $order['express_type'] = $value->express_type;
            $order['created_at'] = $value->created_at;
            $attr = array();
            foreach (explode(',', $value->goods_attr) as $a => $b) {
                $attr[] = attribute::find($b)->attr_name;
            }
            $value->attr = $attr;
            $newData[] = $value;
        }
        $order['data'] = $newData;
        // echo DNS1D::getBarcodePNGPath("4445645656", "C128");
        $order['barcode'] = DNS1D::getBarcodePNG($orderNum, 'C128');

        // dd($order);
        //  传值到前台打印模板
        return view('admin.print-goods-list', compact('order'));
    }

    // 获取未发货订单
    public function no()
    {
        // 获取用户的所有订单
        $order = order::orderBy('order.id', 'ASC')
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
            ->leftJoin('ads', 'ads.id', '=', 'order.ads')
            ->select('user.user_nickname', 'user.user_name', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads', 'ads.name', 'ads.phone', 'order.*')
            ->where('order.end', '0')
            ->where('order.status', '1')
            ->paginate(20);
        // ->groupBy('num');

        $orderInfo = $order->unique('num');

        foreach ($orderInfo as $key => $value) {
            // dd($value);
            $attr = array();
            foreach (explode(',', $value->goods_attr) as $a => $b) {
                $attr[] = attribute::find($b)->attr_name;
            }
            $value->attr = $attr;
            $data[] = $value;
        }
        // dd($data);
        return view('admin.order-list-no', compact('data', 'order'));
    }

    public function send($num)
    {
        // 后期接入短信
        if (order::where('num', $num)
            ->where('status', '1')
            ->whereNotNull('express')
            ->whereNotNull('express_status')
            ->whereNotNull('express_type')
            ->update(['end' => '1'])) {
            $result = $this->result('success', '发货成功!');
        } else {
            $result = $this->result('fail', 'ERROR!发货失败!');
        }

        return $result;
    }

    // 获取已发货订单
    public function on()
    {
        // 获取用户的所有订单
        $order = order::orderBy('order.id', 'DESC')
            ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
            ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
            ->leftJoin('ads', 'ads.id', '=', 'order.ads')
            ->select('user.user_nickname', 'user.user_name', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads', 'ads.name', 'ads.phone', 'order.*')
            ->where('order.end', '1')
            ->where('order.status', '1')
            ->paginate(20);
        // ->groupBy('num');

        $orderInfo = $order->unique('num');
        $data = array();
        foreach ($orderInfo as $key => $value) {
            // dd($value);
            $attr = array();
            foreach (explode(',', $value->goods_attr) as $a => $b) {
                $attr[] = attribute::find($b)->attr_name;
            }
            $value->attr = $attr;
            $data[] = $value;
        }
        // dd($data);
        return view('admin.order-list-on', compact('data', 'order'));
    }

    // 帮助用户下单
    public function help()
    {
        return view('admin.order-help');
    }

    public function helpData()
    {
        $input = Input::all();
        if ($input['code'] == session('smsCode')) {
            $num = date('YmdHis').rand(10000000, 99999999);
            $data = explode(',', $input['goods']);
            $goods = array();
            foreach ($data as $key => $value) {
                if ($value) {
                    $goods[$key]['num'] = explode('^', $value)['0'];
                    $goods[$key]['goods_id'] = explode('~', explode('^', $value)['1'])['0'];
                    $goods[$key]['attr'] = explode('|', explode('~', $value)['1']);
                }
            }
            // print_r($goods);die();
            $info = array();
            $total_old = 0;
            foreach ($goods as $key => $value) {
                $b = 0;
                $a = goods::find($value['goods_id'])->goods_point;
                foreach ($value['attr'] as $k => $v) {
                    $b += attribute::find($v)->attr_point;
                }
                $value['point'] = ($a + $b) * $value['num'];
                $total_old += $value['point'];
                if ($input['ads'] == 0) {
                    $value['ads'] = '';
                } else {
                    $value['ads'] = $input['ads'];
                }
                $info[] = $value;
            }
            // print_r($total);
            $user = User::where('user_uuid', $input['uuid'])->first();
            switch ($user->user_rank) {
                case '2':
                    $sale = 0.8;
                    break;
                case '3':
                    $sale = 0.75;
                    break;
                case '4':
                    $sale = 0.7;
                    break;
                case '5':
                    $sale = 0.65;
                    break;
                case '6':
                    $sale = 0.6;
                    break;
                default:
                    $sale = 1;
                    break;
            }
            $total = $total_old * $sale;
            if (($user->user_point + $user->user_point_give) >= $total) {
                if ($user->user_point_give > $total) {
                    $newPoint = $user->user_point;
                    $newPointGive = $user->user_point_give - $total;
                } else {
                    $newPoint = ($user->user_point + $user->user_point_give) - $total;
                    $newPointGive = 0;
                }
                DB::beginTransaction();
                try {
                    //订单入库
                    foreach ($info as $key => $value) {
                        $order = new order();
                        $order->num = $num;
                        $order->type = 2;
                        $order->price = $value['point'];
                        $order->point = $value['point'];
                        $order->goods_id = $value['goods_id'];
                        $order->goods_num = $value['num'];
                        if (count($value['attr']) > 1) {
                            $order->goods_attr = $value['attr']['0'].','.$value['attr']['1'];
                        } else {
                            $order->goods_attr = $value['attr']['0'];
                        }
                        $order->ads = $value['ads'];
                        $order->uuid = $input['uuid'];
                        $order->mark = $input['log'];
                        $order->status = 1;
                        $order->save();
                    }
                    // 扣除积分
                    User::where('user_uuid', $input['uuid'])
                            ->update([
                                'user_point_give' => $newPointGive,
                                'user_point' => $newPoint,
                            ]);
                    // 返还积分至用户积分赠送账户
                    $log = new log_point_user();
                    $log->uuid = $user->user_uuid;
                    $log->point = $user->user_point;
                    $log->new_point = $newPoint;
                    $log->point_give = $user->user_point_give;
                    $log->new_point_give = $newPointGive;
                    $log->type = 12;
                    $log->status = 1;
                    $log->add = 2;
                    // dd($log);
                    $log->save();
                    DB::commit();
                    $result = $this->result('success', '订单录入成功!');
                } catch (\Exception $e) {
                    //接收异常处理并回滚
                    DB::rollBack();
                    $result = $this->result('fail', 'ERROR!当前系统繁忙,订单录入失败!');
                }
            } else {
                $result = $this->result('fail', '用户积分不足以抵扣商品总价!');
            }
        } else {
            $result = $this->result('fail', '短信验证码验证失败!');
        }

        // die;
        return $result;
    }

    public function code()
    {
        $input = Input::all();
        if ($this->sendSms($input['phone'])) {
            $result = $this->result('success', '发送成功!');
        } else {
            $result = $this->result('fail', '短信发送失败!');
        }

        return $result;
    }

    public function select()
    {
        $input = Input::all();
        $data = goods::where('goods_name', 'like', '%'.$input['text'].'%')
            ->get();
        if ($data) {
            $result = $this->result('success', '成功', $data);
        } else {
            $result = $this->result('fail', '未查询到商品!');
        }

        return $result;
    }

    public function goodsInfo($goodsId)
    {
        $goods = goods::where('id', $goodsId)
            ->first();
        // 获取商品的属性值
        $attr = attribute::orderBy('attr_pid', 'DESC')
            ->where('status', '1')
            ->where('goods_id', $goodsId)
            ->get();
        if ($attr->isNotEmpty()) {
            $attr = $this->handleAttr($attr->toArray());
        }
        // dd($attr);
        // 计算商品库存
        $depot = attribute::where('goods_id', $goodsId)
            ->where('status', '1')
            ->get()
            ->sum('attr_depot');
        $data['goods'] = $goods;
        $data['attr'] = $attr;
        $data['depot'] = $depot;
        $result = $this->result('success', '添加成功!', $data);

        return $result;
    }

    public function editMark()
    {
        $input = Input::all();
        // dd($input);
        if (order::where('num', $input['num'])->update([
            'mark' => $input['mark'],
        ])) {
            $result = $this->result('success', '修改成功!');
        } else {
            $result = $this->result('fail', '修改失败,未知的错误!');
        }

        return $result;
    }

    public function getUser()
    {
        $input = Input::all();
        if (ctype_digit($input['text'])) {
            $user = User::where('user_phone', $input['text'])
                ->first();
        } else {
            $user = User::where('user_name', $input['text'])
                ->first();
        }
        if ($user) {
            $data['user'] = $user;
            $ads = ads::orderBy('status', 'DESC')
                ->where('uuid', $user->user_uuid)
                ->get();
            if ($ads->isEmpty()) {
                $data['ads'] = '';
            } else {
                $data['ads'] = $ads;
            }
            $result = $this->result('success', '查询成功!', $data);
        } else {
            $result = $this->result('fail', '未查询到用户!');
        }

        return $result;
    }

    public function remove()
    {
        $input = Input::all();
        $order = order::where('num', $input['num'])
            ->where('type', '2')
            ->where('express_status', '0')
            ->where('status', '1')
            ->where('end', '0')
            ->get();
        if ($order->isNotEmpty()) {
            $user = '';
            foreach ($order as $key => $value) {
                $user = user::where('user_uuid', $value->uuid)
                    ->first();
            }
            if ($user) {
                DB::beginTransaction();
                try {
                    // 删除订单
                    order::where('num', $input['num'])
                        ->where('type', '2')
                        ->where('express_status', '0')
                        ->where('status', '1')
                        ->where('end', '0')
                        ->delete();
                    // 返还积分至用户积分赠送账户
                    $log = new log_point_user();
                    $log->uuid = $user->user_uuid;
                    $log->point_give = $user->user_point_give;
                    $log->new_point_give = $user->user_point_give + ($order->sum('point') * $order['0']->sale);
                    $log->type = 11;
                    $log->status = 1;
                    $log->add = 1;
                    // dd($log);
                    $log->save();
                    $newPointGive = $user->user_point_give + ($order->sum('point') * $order['0']->sale);
                    User::where('user_uuid', $user->user_uuid)
                        ->update([
                            'user_point_give' => $newPointGive,
                        ]);
                    // // 增加商品库存
                    // foreach ($order as $key => $value) {
                    // 	$attribute = attribute::find($value->)
                    // }
                    DB::commit();
                    $result = $this->result('success', '订单取消成功!');
                } catch (\Exception $e) {
                    //接收异常处理并回滚
                    DB::rollBack();
                    $result = $this->result('fail', 'ERROR!当前系统繁忙,订单取消失败!');
                }
            } else {
                $result = $this->result('fail', '未查询到用户数据!');
            }
        } else {
            $result = $this->result('fail', '未查询到订单数据!');
        }

        return $result;
    }

    public function searchOrder()
    {
        $input = Input::all();
        // dd($input);
        // 根据查询项进行搜索
        switch ($input['type']) {
            // 订单号
            case '1':
                $data = order::orderBy('order.id', 'DESC')
                    ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                    ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
                    ->leftJoin('ads', 'ads.id', '=', 'order.ads')
                    ->select('user.user_nickname', 'user.user_name', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads', 'ads.name', 'ads.phone', 'order.*')
                    // ->where('order.end','1')
                    ->where('order.status', '1')
                    ->where('order.num', 'like', '%'.$input['val'].'%')
                    ->get();
                break;
            // 收货人
            case '2':
                $data = order::orderBy('order.id', 'DESC')
                    ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                    ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
                    ->leftJoin('ads', 'ads.id', '=', 'order.ads')
                    ->select('user.user_nickname', 'user.user_name', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads', 'ads.name', 'ads.phone', 'order.*')
                    // ->where('order.end','1')
                    ->where('order.status', '1')
                    ->where('user.user_name', 'like', '%'.$input['val'].'%')
                    ->get();
                break;
            // 下单时间
            case '3':
                $data = order::orderBy('order.id', 'DESC')
                    ->leftJoin('goods', 'goods.id', '=', 'order.goods_id')
                    ->leftJoin('user', 'user.user_uuid', '=', 'order.uuid')
                    ->leftJoin('ads', 'ads.id', '=', 'order.ads')
                    ->select('user.user_nickname', 'user.user_name', 'user.user_pic', 'user.user_rank', 'goods.goods_name', 'goods.goods_pic', 'ads.province', 'ads.city', 'ads.area', 'ads.ads', 'ads.name', 'ads.phone', 'order.*')
                    // ->where('order.end','1')
                    ->where('order.status', '1')
                    ->where('order.created_at', 'like', $input['val'].'%')
                    ->get();
                break;
        }
        if ($data->isEmpty()) {
            $result = $this->result('fail', '未查询到数据!');
        } else {
            $result = $this->result('success', '成功!', $data);
        }

        return $result;
    }
}
