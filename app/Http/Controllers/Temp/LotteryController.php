<?php

namespace App\Http\Controllers\Temp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

use \Milon\Barcode\DNS1D;
// 加载Model
use App\Model\User;
use App\Model\lottery;
use App\Model\lottery_goods;
use App\Model\admin;
use Log;
class LotteryController extends Controller
{
    public function index()
    {
    	if (empty(session('user'))) {
            $app = app('wechat.official_account');
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect();
            return $response;
        }else{
            $app = app('wechat.official_account');
            // 检查用户是否已经抽过奖
            $lottery = lottery::where('uuid',session('user')->user_uuid)
            	->where('in','1')
            	->first();
            if ($lottery) {
            	$lotteryStatus = true;
            	$code = $lottery->id."|".$lottery->lottery_key;
            	$lotteryCode = DNS1D::getBarcodePNG($code, "C128");
            }else{
            	$lotteryStatus = false;
            }

            // 获取到中奖信息前10条
            $openLotteryInfo = lottery::orderBy('id','DESC')
            	->where('lottery_key','!=','0')
            	->where('in','1')
            	->leftJoin('user','lottery.uuid','=','user.user_uuid')
            	->select('user.user_nickname','user.user_pic','lottery.*')
            	->take(10)
            	->get();
           	if (!$lotteryStatus) {
           		// 开奖算法
		    	// 奖品为5种
		    	// 一等奖 巴厘岛  2
		    	// 二等奖 茶		2
		    	// 三等奖 白咖啡	5
		    	// 四等奖 黑咖啡	5
		    	// 五等奖 矿泉水	10

		    	// 人群基数为50 共24件商品
		    	// 中奖比例为2:1

		    	// 查询出奖品的余量
		    	$w1 = lottery::where('lottery_key','1')
		    		->where('in','1')
		    		->get()->count();
		    	$w2 = lottery::where('lottery_key','2')
		    		->where('in','1')
		    		->get()->count();
		    	$w3 = lottery::where('lottery_key','3')
		    		->where('in','1')
		    		->get()->count();
		    	$w4 = lottery::where('lottery_key','4')
		    		->where('in','1')
		    		->get()->count();
		    	$w5 = lottery::where('lottery_key','5')
		    		->where('in','1')
		    		->get()->count();


		    	// 参与人的总数
		    	$lotteryCount = lottery::get()->where('in','1')->count();
		    	// 中奖的人的总数
		    	$lotteryInPeople = lottery::where('lottery_key','!=','0')
		    		->get()->count();
		    	// 目前中奖人的比例 总量为:2的平均值
		    	if ($lotteryInPeople != 0 && $lotteryCount != 0) {
		    		$lotteryInValue = $lotteryInPeople / $lotteryCount;
		    	}else{
		    		$lotteryInValue = 0;
		    	}
		    	// 如果目前中奖几率低于平均线,则直接中奖.否则执行随机
		    	// 如果超过平均线一个点.则强制性不中奖
		    	// 如果低于平均线一个点,则强制性中奖
		    	if ($lotteryInValue > 0.5) {
		    		$key = 2;
		    	}else{
		    		// 中奖流程
		    		$roll = rand(0,3);
		    		switch ($roll) {
		    			// 一二等奖开奖流程
		    			case '0':
		    				$rolls = rand(1,9);
			    			if ($rolls % 2 == 0) {
			    				// 一等奖
			    				// 检查是否还有一等奖的奖品
			    				if ($w1 > 1) {
			    					// 一等奖派送完了 检查二等奖奖品
			    					if ($w2 > 1) {
			    						// 中了三等奖
			    						if ($w3 > 4) {
			    							if ($w4 > 4) {
			    								if ($w5 > 9) {
			    									$key = 2;
			    								}else{
			    									$key = 4;
			    								}
			    							}else{
			    								$key = 3;
			    							}
			    						}else{
			    							$key = 7;
			    						}
			    					}else{
			    						$key = 1;
			    					}
			    				}else{
			    					// 中了一等奖
			    					$key = 6;
			    				}
			    			}else{
			    				if ($w2 > 1) {
			    					if ($w3 > 4) {
			    						if ($w4 > 4) {
			    							if ($w5 > 9) {
			    								$key = 2;
			    							}else{
			    								$key = 4;
			    							}
			    						}else{
			    							$key = 3;
			    						}
			    					}else{
			    						$key = 7;
			    					}
			    				}else{
			    					$key = 1;
			    				}
			    			}
		    			break;

		    			case '1':
							if ($w3 > 4) {
			    				if ($w4 > 4) {
			    					if ($w5 > 9) {
			    						$key = 2;
			    					}else{
			    						$key = 4;
			    					}
			    				}else{
			    					$key = 3;
			    				}
			    			}else{
			    				$key = 7;
			    			}
						break;

						case '2':
							if ($w4 > 4) {
			    				if ($w5 > 9) {
			    					$key = 2;
			    				}else{
			    					$key = 4;
			    				}
			    			}else{
			    				$key = 3;
			    			}
						break;

						case '3':
							if ($w5 > 9) {
			    				$key = 2;
			    			}else{
			    				$key = 4;
			    			}
						break;
		    		}
		    	}
		    	// 存入开奖结果
		    	$lottery = new lottery;
		    	$lottery->uuid = session('user')->user_uuid;
		    	switch ($key) {
		    		case '1':
		    			$lottery->lottery_key = 2;
		    			$msg = 'images/temp/lottery-03.png';
		    			break;
		    		case '2':
		    			$lottery->lottery_key = 0;
		    			$msg = 'images/temp/lottery-06.png';
		    			break;
		    		case '3':
		    			$lottery->lottery_key = 3;
		    			$msg = 'images/temp/lottery-02.png';
		    			break;
		    		case '4':
		    			$lottery->lottery_key = 5;
		    			$msg = 'images/temp/lottery-01.png';
		    			break;
		    		case '5':
		    			$lottery->lottery_key = 2;
		    			$msg = 'images/temp/lottery-03.png';
		    			break;
		    		case '6':
		    			$lottery->lottery_key = 1;
		    			$msg = 'images/temp/lottery-09.png';
		    			break;
		    		case '7':
		    			$lottery->lottery_key = 4;
		    			$msg = 'images/temp/lottery-04.png';
		    			break;
		    		case '8':
		    			$lottery->lottery_key = 5;
		    			$msg = 'images/temp/lottery-01.png';
		    			break;
		    		default:
		    			$lottery->lottery_key = 0;
		    			break;
		    	}
		    	if ($lottery->save()) {
		    		$id = $lottery->id;
		    		return view('temp.lottery',compact('app','lotteryStatus','msg','key','openLotteryInfo','id'));
		    	}else{
		    		return redirect('lottery','');
		    	}
           	// dd($lotteryStatus);
           	}
           	$id = '';
           	$key = 2;
           	$msg = '中了幸运奖';
           	return view('temp.lottery',compact('app','lotteryStatus','msg','key','openLotteryInfo','lotteryCode','id'));
        }
    }

    public function setLottery()
    {
    	$input  = Input::all();
    	if (lottery::where('id',$input['id'])
    		->update(["in"=>'1'])) {
    		$result = $this->result('success','成功!','');
    	}else{
    		$result = $this->result('fail','','');
    	}
    	return $result;
    }

    public function scanBarcode()
    {
	// dd(session('user')->user_uuid);
    	$app = app('wechat.official_account');
    	return view('temp.scan',compact('app'));
    }
    public function getBarcode()
    {
    	$input = Input::all();
    	// 验证工作人员身份
    	if (admin::where('uuid',session('user')->user_uuid)->first()) {
    		$str = explode('|',$input['key']);
    		$key = $str['1'];
    		switch ($key) {
    			case '1':
    				$msg = "中奖奖品为: 巴厘岛纯玩5天4夜套餐8折卡";
    				break;
    			case '2':
    				$msg = "中奖奖品为: 价值736的皇室红茶礼盒";
    				break;
    			case '3':
    				$msg = "中奖奖品为: 价值268白咖啡";
    				break;
    			case '4':
    				$msg = "中奖奖品为: 价值268黑咖啡";
    				break;
    			case '5':
    				$msg = "中奖奖品为: 价值198思源水";
    				break;
    			case '0':
    				$msg = "中奖奖品为: 幸运奖";
    				break;
    		}
    		$id = explode(",",$str['0'])['1'];
    		if (lottery::where('id',$id)
    			->where('status','0')
    			->update(['status'=>'1'])) {
    			$result = $this->result('success',$msg,'');
    		}else{
    			$result = $this->result('fail','ERROR!数据处理失败,或已经派过奖了!','');
    		}
    	}else{
    		$result = $this->result('fail','你走错路了,小朋友!!','');
    	}
    	return $result;
    }
}
