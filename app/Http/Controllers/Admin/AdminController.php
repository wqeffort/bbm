<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
// 加载Model
use App\Model\User;
use App\Model\admin;
use App\Model\join;
use App\Model\log_login;
use Illuminate\Http\Response;
use App\Model\ticket;
use App\Model\ticket_order;
use Log;
class AdminController extends Controller
{
    public function list()
    {
    	if (session('admin')->rank == '9') {
    		$admin = admin::orderBy('admin.id','ASC')
	    		->where('admin.rank','<',session('admin')->rank)
	    		->leftJoin('user','admin.uuid','=','user.user_uuid')
	    		->select('admin.*','user.user_name','user.user_nickname','user.user_pic','user.user_uuid','user.user_openid')
	    		->paginate(15);
    	}else{
    		// 查询出所有所属部门的管理员
	    	$admin = admin::orderBy('admin.id','ASC')
	    		->where('admin.cate',session('admin')->cate)
	    		->where('admin.rank','<',session('admin')->rank)
	    		->leftJoin('user','admin.uuid','=','user.user_uuid')
	    		->select('admin.*','user.user_name','user.user_nickname','user.user_pic','user.user_uuid','user.user_openid')
	    		->paginate(15);
    	}
        // dd(session('admin'));
    	return view('admin.admin-list',compact('admin'));
    }

    public function status($id)
    {
        $common = new Controller;
        if (admin::find($id)->status) {
            $newStatus = '0';
        }else{
            $newStatus = '1';
        }
        if (isset($newStatus)) {
            if (admin::where('id',$id)
                ->update(['status'=>$newStatus])) {
                $result = $common->result('success','修改成功!','');
            }else{
                $result = $common->result('fail','ERROR!修改状态失败!','');
            }
        }else{
            $result = $common->result('fail','修改状态失败!','');
        }
        return $result;
    }

    public function del($id)
    {
    	if (admin::where('id',$id)->delete()) {
    		$result = $this->result('success','删除管理员成功!');
    	}else{
    		$result = $this->result('fail','ERROR!删除管理员失败!');
    	}
    	return $result;
    }

    public function add()
    {
    	$admin = session('admin');
    	return view('admin.admin-add',compact('admin'));
    }

    public function create()
    {
    	$input = Input::all();
    	// 先进行防重查询
    	if (admin::where('uuid',$input['uuid'])->first()) {
    		$result = $this->result('fail','该用户已经是管理员.无法再次进行添加!','');
    	}else{
    		$admin = new admin;
    		$admin->cate = session('admin')->cate;
    		$admin->rank = session('admin')->rank - 1;
    		$admin->uuid = $input['uuid'];
    		$admin->status = 1;
    		if ($admin->save()) {
    			$result = $this->result('success','添加管理员成功');
    		}else{
    			$result = $this->result('fail','ERROR!添加管理员失败!');
    		}
    	}
    	return $result;
    }
    public function getUser()
    {
        $input = Input::all();
        $user = User::where('user_phone',$input['phone'])->first();
        // dd($user);
        if ($user) {
            if ($user->user_uid) {
                $result = $this->result('success','查询成功!',$user);
            }else{
                $result = $this->result('fail','未查询到用户信息或用户未进行实名认证!');
            }
        }else{
            $result = $this->result('fail','未查询到用户信息!');
        }
        return $result;
    }

    public function getJoin()
    {
        $input = Input::all();
        $user = User::where('user_phone',$input['phone'])->first();
        $join = join::where('uuid',$user->user_uuid)
            ->where('status','1')
            ->first();
        if ($user && $join) {
            $result = $this->result('success','查询成功!',$user);
        }else{
            $result = $this->result('fail','未查询到加盟商信息!');
        }
        return $result;
    }

    public function ticket()
    {
        $data = ticket_order::orderBy('id', 'DESC')
            ->where('ticket_order.status','1')
            ->leftJoin('ticket','ticket.id','=','ticket_order.ticket_id')
            ->leftJoin('user','user.user_uuid','=','ticket_order.uuid')
            ->select('ticket_order.*','ticket.desk','ticket.buy','user.user_name','user.user_phone')
            ->paginate(50);
        $desk = ticket::get();
        return view('admin.ticket',compact('data','desk'));
    }
}
