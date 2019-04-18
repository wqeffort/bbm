<?php
namespace App\Http\Controllers\Api\Community;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Log;

// 加载Model
use App\Model\User;
use App\Model\ja_photo;
use App\Model\ja_community;
use App\Model\ja_follow;
use App\Model\ja_zan;
use App\Model\ja_comment;
use App\Model\ja_comment_zan;
use App\Model\ja_no_look;

// 加载OSS储存对象
use App\Http\Controllers\Api\Oss;

class CommunityController extends Controller
{

    /**
     * [add 发文]
     * @param token
     * @param uuid
     * @param key
     * @param photo array 用户上传的照片
     * @param text 文字
     * @param lng 经度
     * @param lat 纬度
     * @param poi poiID
     * @param poi_text poi地址中文描述
     * @param remind array(uuid,uuid)
     * @param type int 信息类型 图文信息:1 视频文信息:2
     */
    public function add()
    {
        $input = Input::all();
        // Log::notice($input);
        // 先存储发送朋友圈的数据
        $community = new ja_community;
        $community->uuid = $input['uuid'];
        $community->text = $input['text'];
        $community->lng = $input['lng'];
        $community->lat = $input['lat'];
        $community->poi = $input['poi'];
        $community->poi_text = $input['poi_text'];
        $community->type = $input['type'];
        $remind = '';
        $user = User::where('user_uuid',$input['uuid'])->first();
        if (json_decode($input['remind'])) {
            foreach (json_decode($input['remind']) as $key => $value) {
                $remind .= $value."|";
            }
            $this->push(json_decode($input['remind']),3,'来自社区消息',$user->user_nickname.'在发文中提到了你','app','community/notice',$user->user_pic);
        }
        $community->remind = $remind;
        // Log::notice($community);
        if ($community->save()) {
            if ($input['photo']) {
                // 存入照片
                $count = 0;
                foreach (json_decode($input['photo']) as $key => $value) {
                    $photo = new ja_photo;
                    $photo->uuid = $input['uuid'];
                    $photo->photo = $value;
                    $photo->community_id = $community->id;
                    $photo->status = 1;
                    if ($photo->save()) {
                        $count++;
                        $result = $this->result('success','发文成功!','');
                    }else{
                        $result = $this->result('fail','照片未储存,数据储存失败!');
                    }
                }
            }else{
                $result = $this->result('success','发文成功!','');
            }
        }else{
            $result = $this->result('fail','数据储存失败!');
        }
        return $result;

    }


    /**
     * [data 下拉刷新获取数据]
     * @param uuid
     * @param key
     * @param token
     * @param community_id
     */
    public function now()
    {
        $data = array();
        $input = Input::all();
        // 获取屏蔽的用户
        $noLook = ja_no_look::where('uuid',$input['uuid'])
            ->where('status','1')
            ->select('to')
            ->get();
        $noArray = array();
        if ($noLook->isNotEmpty()) {
            foreach ($noLook as $key => $value) {
                $noArray[] = $value->to;
            }
            $info = ja_community::orderBy('ja_community.id','DESC')
                ->whereNotIn('ja_community.uuid',$noArray)
                ->where('ja_community.status','1')
                ->leftJoin('user','user.user_uuid','=','ja_community.uuid')
                ->select('user.user_pic','user.user_nickname','ja_community.*')
                ->where('ja_community.id','>',$input['community_id'])
                ->take(20)
                ->get();
        }else{
            $info = ja_community::orderBy('ja_community.id','DESC')
                ->where('ja_community.status','1')
                ->leftJoin('user','user.user_uuid','=','ja_community.uuid')
                ->select('user.user_pic','user.user_nickname','ja_community.*')
                ->where('ja_community.id','>',$input['community_id'])
                ->take(20)
                ->get();
        }
        // dd($info);
        foreach ($info as $key => $value) {
            // 检查信息查看的条件
            if ($value->secret) {
                switch ($value->secret) {
                    case '1':
                        // 检查发帖人是否关注了用户
                        $a = ja_follow::where('uuid_a',$input['uuid'])
                            ->where('uuid_b',$value->uuid)
                            ->where('in_b','1');
                        $status = ja_follow::where('uuid_a',$value->uuid)
                            ->where('uuid_b',$input['uuid'])
                            ->where('in_a','1')
                            ->union($a)
                            ->first();
                        if ($status) {
                            if (ja_zan::where('community_id',$value->id)
                                ->where('to',$input['uuid'])
                                ->where('status','1')
                                ->first()) {
                                $value['is_zan'] = true;
                            }else{
                                $value['is_zan'] = false;
                            }
                            $value['is_follow'] = true;
                            $value['comment_count'] = ja_comment::where('community_id',$value->id)
                                ->where('status','1')
                                ->get()->count();
                            $photo = ja_photo::where('community_id',$value->id)
                                ->where('status','1')
                                ->get();
                            if (count($photo) >= 1) {
                                $value['photo'] = $photo;
                            }else{
                                $value['photo'] = array();
                            }
                            $data[] = $value;
                        }
                        break;
                }
            }else{
                // 用户是否关注了发帖人
                $a = ja_follow::where('uuid_a',$input['uuid'])
                    ->where('uuid_b',$value->uuid)
                    ->where('in_a','1');
                $status = ja_follow::where('uuid_a',$value->uuid)
                    ->where('uuid_b',$input['uuid'])
                    ->where('in_b','1')
                    ->union($a)
                    ->first();
                // dd($status);
                if ($status) {
                    $value['is_follow'] = true;
                }else{
                    $value['is_follow'] = false;
                }
                if (ja_zan::where('community_id',$value->id)
                    ->where('to',$input['uuid'])
                    ->where('status','1')
                    ->first()) {
                    $value['is_zan'] = true;
                }else{
                    $value['is_zan'] = false;
                }
                // 获取评论总数
                $value['comment_count'] = ja_comment::where('community_id',$value->id)
                    ->where('status','1')
                    ->get()->count();
                $photo = ja_photo::where('community_id',$value->id)
                    ->where('status','1')
                    ->get();
                if (count($photo) >= 1) {
                    $value['photo'] = $photo;
                }else{
                    $value['photo'] = array();
                }
                $data[] = $value;
            }
        }
        $result = $this->result('success','获取数据成功!',$data);
        return $result;
    }


    /**
     * [data 上拉获取更多数据]
     * @param uuid
     * @param key
     * @param token
     * @param community_id [上拉的最后一条ID]
     */
    public function ago()
    {
        $input = Input::all();
        // 获取屏蔽的用户
        $noLook = ja_no_look::where('uuid',$input['uuid'])
            ->where('status','1')
            ->select('to')
            ->get();
        if ($noLook->isNotEmpty()) {
            foreach ($noLook as $key => $value) {
                $noArray[] = $value->to;
            }
            $info = ja_community::orderBy('ja_community.id','DESC')
                ->where('ja_community.id','<',$input['community_id'])
                ->whereNotIn('ja_community.uuid',$noArray)
                ->where('ja_community.status','1')
                ->leftJoin('user','user.user_uuid','=','ja_community.uuid')
                ->select('user.user_pic','user.user_nickname','ja_community.*')
                ->take(20)
                ->get();
        }else{
            $info = ja_community::orderBy('ja_community.id','DESC')
                ->where('ja_community.id','<',$input['community_id'])
                ->where('ja_community.status','1')
                ->leftJoin('user','user.user_uuid','=','ja_community.uuid')
                ->select('user.user_pic','user.user_nickname','ja_community.*')
                ->take(20)
                ->get();
        }
        if ($info->isNotEmpty()) {
            foreach ($info as $key => $value) {
                // 检查信息查看的条件
                if ($value->secret) {
                    switch ($value->secret) {
                        case '1':
                            // 检查发帖人是否关注了用户
                            $a = ja_follow::where('uuid_a',$input['uuid'])
                                ->where('uuid_b',$value->uuid)
                                ->where('in_a','1');
                            $status = ja_follow::where('uuid_a',$value->uuid)
                                ->where('uuid_b',$input['uuid'])
                                ->where('in_b','1')
                                ->union($a)
                                ->first();
                            if ($status) {
                                $value['is_follow'] = true;
                                if (ja_zan::where('community_id',$value->id)
                                    ->where('to',$input['uuid'])
                                    ->where('status','1')
                                    ->first()) {
                                    $value['is_zan'] = true;
                                }else{
                                    $value['is_zan'] = false;
                                }
                                $value['comment_count'] = ja_comment::where('community_id',$value->id)
                                    ->where('status','1')
                                    ->get()->count();
                                $photo = ja_photo::where('community_id',$value->id)
                                    ->where('status','1')
                                    ->get();
                                if (count($photo) >= 1) {
                                    $value['photo'] = $photo;
                                }else{
                                    $value['photo'] = array();
                                }
                                $data[] = $value;
                            }
                            break;
                    }
                }else{
                    // 检查发帖人是否关注了用户
                    $a = ja_follow::where('uuid_a',$input['uuid'])
                        ->where('uuid_b',$value->uuid)
                        ->where('in_a','1');
                    $status = ja_follow::where('uuid_a',$value->uuid)
                        ->where('uuid_b',$input['uuid'])
                        ->where('in_b','1')
                        ->union($a)
                        ->first();
                    if ($status) {
                        $value['is_follow'] = true;
                    }else{
                        $value['is_follow'] = false;
                    }
                    if (ja_zan::where('community_id',$value->id)
                        ->where('to',$input['uuid'])
                        ->where('status','1')
                        ->first()) {
                        $value['is_zan'] = true;
                    }else{
                        $value['is_zan'] = false;
                    }
                    $value['comment_count'] = ja_comment::where('community_id',$value->id)
                                ->where('status','1')
                                ->get()->count();
                    $photo = ja_photo::where('community_id',$value->id)
                        ->where('status','1')
                        ->get();
                    if (count($photo) >= 1) {
                        $value['photo'] = $photo;
                    }else{
                        $value['photo'] = array();
                    }
                    $data[] = $value;
                }
            }
            $result = $this->result('success','获取数据成功!',$data);
        }else{
            $result = $this->result('fail','没有更多数据了!');
        }
        return $result;
    }



    /**
     * [addFollow 添加关注]
     *
     * @param token
     * @param uuid
     * @param key
     * @param uuid 用户uuid
     * @param follow 关注的人
     */
    public function addFollow()
    {
        $input = Input::all();
        // 检查是否存在关联关系
        // 检查发帖人是否关注了用户
        $a = ja_follow::where('uuid_a',$input['uuid'])
            ->where('uuid_b',$input['follow']);
        $info = ja_follow::where('uuid_a',$input['follow'])
            ->where('uuid_b',$input['uuid'])
            ->union($a)
            ->first();
        $user = User::where('user_uuid',$input['uuid'])->first();
        if ($info) {
            // 检查数据位置,修改对应值
            // 获取键名
            $key = explode('_',array_search($input['uuid'],$info->toArray()))['1'];
            if (ja_follow::find($info->id)->update([
                "in_".$key => 1
            ])) {
                $this->push(array($this->uuidStr($input['follow'])),'3','来自社区消息',$user->user_nickname.' 关注了你!','app','follow/user',$user->user_pic);
                $result = $this->result('success','关注成功!');
            }else{
                $result = $this->result('fail','关注失败,服务器繁忙,请稍后再试!!');
            }
        }else{
            // 直接进行关注
            $follow = new ja_follow;
            $follow->uuid_a = $input['uuid'];
            $follow->in_a = 1;
            $follow->uuid_b = $input['follow'];
            $follow->in_b = 0;
            if ($follow->save()) {
                $this->push(array($this->uuidStr($input['follow'])),'3','来自社区消息',$user->user_nickname.' 关注了你!','app','follow/user',$user->user_pic);
                $result = $this->result('success','关注成功!');
            }else{
                $result = $this->result('fail','关注失败,服务器繁忙,请稍后再试!');
            }
        }
        return $result;
    }


    /**
     * [cancelFollow 取消关注]
     *
     * @param token
     * @param uuid
     * @param key
     * @param uuid 用户uuid
     * @param follow 取消关注的人
     *
     */
    public function cancelFollow()
    {
        $input = Input::all();
        // 检查是否存在关联关系
        // 检查发帖人是否关注了用户
        $a = ja_follow::where('uuid_a',$input['uuid'])
            ->where('uuid_b',$input['follow']);
        $info = ja_follow::where('uuid_a',$input['follow'])
            ->where('uuid_b',$input['uuid'])
            ->union($a)
            ->first();
        if ($info) {
            // 检查数据位置,修改对应值
            // 获取键名
            $key = explode('_',array_search($input['uuid'],$info->toArray()))['1'];
            if (ja_follow::find($info->id)->update([
                "in_".$key => 0
            ])) {
                $result = $this->result('success','取消关注成功!');
            }else{
                $result = $this->result('fail','取消关注失败,服务器繁忙,请稍后再试!!');
            }
        }else{
            $result = $this->result('fail','ERROR!数据错误,请重新刷新!');
        }
        return $result;
    }


    /**
     * [addZan 点赞]
     * @param token
     * @param uuid
     * @param key
     * @param community_id   数据列id
     * @param zan  被点赞的人
     */
    public function addZan()
    {
        $input = Input::all();
        // 检查是否存在该数据列
        $info = ja_zan::where('uuid',$input['zan'])
            ->where('to',$input['uuid'])
            ->where('community_id',$input['community_id'])
            ->first();
        $user = User::where('user_uuid',$input['uuid'])->first();
        $community = ja_community::find($input['community_id']);
        if ($info) {
            DB::beginTransaction();
            try{
                ja_zan::find($info->id)->update([
                    "status"=>1
                ]);
                $this->push(array($this->uuidStr($input['zan'])),'3',$user->user_nickname.' 赞了你的社区消息!',$community->text,'app','community/'.$input['community_id'],$user->user_pic);
                ja_community::find($input['community_id'])->increment('zan_count');
                DB::commit();
                $result = $this->result('success','点赞成功!');
            }catch (\Exception $e) {
                //接收异常处理并回滚
                DB::rollBack();
                $result = $this->result('fail','ERROR!操作失败,请稍后再试!');
            }
        }else{
            $zan = new ja_zan;
            $zan->uuid = $input['zan'];
            $zan->to = $input['uuid'];
            $zan->status = 1;
            $zan->community_id = $input['community_id'];
            if ($zan->save()) {
                ja_community::find($input['community_id'])->increment('zan_count');
                $this->push(array($this->uuidStr($input['zan'])),'3',$user->user_nickname.' 赞了你的社区消息!',$community->text,'app','community/'.$input['community_id'],$user->user_pic);
                $result = $this->result('success','点赞成功!');
            }else{
                $result = $this->result('fail','ERROR!操作失败,请稍后再试!!');
            }
        }
        return $result;
    }

    public function cancelZan()
    {
        $input = Input::all();
        // 检查是否存在该数据列
        $info = ja_zan::where('uuid',$input['zan'])
            ->where('to',$input['uuid'])
            ->where('community_id',$input['community_id'])
            ->first();
        if ($info) {
            DB::beginTransaction();
            try{
                ja_zan::find($info->id)->update([
                    "status"=>0
                ]);
                ja_community::find($input['community_id'])->decrement('zan_count');
                DB::commit();
                $result = $this->result('success','取消点赞成功!');
            }catch (\Exception $e) {
                //接收异常处理并回滚
                DB::rollBack();
                $result = $this->result('fail','ERROR!操作失败,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','ERROR!数据错误,请重新刷新!');
        }
        return $result;
    }

    public function addComment()
    {
        $input = Input::all();
        $comment = new ja_comment;
        $comment->community_id = $input['community_id'];
        $comment->text = $input['text'];
        $comment->uuid = $input['uuid'];
        $comment->pid = $input['pid'];
        if ($comment->save()) {
            $community = ja_community::find($input['community_id']);
            $user = User::where('user_uuid',$input['uuid'])->select('user_uuid','user_pic','user_nickname')->first();
            $this->push(array($this->uuidStr($community->uuid)),'3',$user->user_nickname.' 评论了您的发文!',$input['text'],'app','community/'.$input['community_id'],$user->user_pic);
            $result = $this->result('success','评论成功!');
        }else{
            $result = $this->result('fail','评论失败,请稍后再试!');
        }
        return $result;
    }


    /**
     * [addZan 评论点赞]
     * @param token
     * @param uuid
     * @param key
     * @param comment_id   数据列id
     * @param zan  被点赞的人
     */
    public function commentAddZan()
    {
        $input = Input::all();
        // 检查是否存在该数据列
        $info = ja_comment_zan::where('uuid',$input['zan'])
            ->where('to',$input['uuid'])
            ->where('comment_id',$input['comment_id'])
            ->first();
        $comment = ja_comment::find($input['comment_id']);
        $user = User::where('user_uuid',$input['uuid'])->first();
        if ($info) {
            DB::beginTransaction();
            try{
                ja_comment_zan::find($info->id)->update([
                    "status"=>1
                ]);
                ja_comment::find($input['comment_id'])->increment('zan_count');
                DB::commit();
                $this->push(array($this->uuidStr($input['zan'])),'3',$user->user_nickname.' 赞了你的评论!',$comment->text,'app','comment/'.$input['comment_id'],$user->user_pic);
                $result = $this->result('success','点赞成功!');
            }catch (\Exception $e) {
                //接收异常处理并回滚
                DB::rollBack();
                $result = $this->result('fail','ERROR!操作失败,请稍后再试!');
            }
        }else{
            $zan = new ja_comment_zan;
            $zan->uuid = $input['zan'];
            $zan->to = $input['uuid'];
            $zan->status = 1;
            $zan->comment_id = $input['comment_id'];
            if ($zan->save()) {
                ja_comment::find($input['comment_id'])->increment('zan_count');
                $this->push(array($this->uuidStr($input['zan'])),'3',$user->user_nickname.' 赞了你的评论!',$comment->text,'app','comment/'.$input['comment_id'],$user->user_pic);
                $result = $this->result('success','点赞成功!');
            }else{
                $result = $this->result('fail','ERROR!操作失败,请稍后再试!!');
            }
        }
        return $result;
    }

    public function commentCancelZan()
    {
        $input = Input::all();
        // 检查是否存在该数据列
        $info = ja_comment_zan::where('uuid',$input['zan'])
            ->where('to',$input['uuid'])
            ->where('comment_id',$input['comment_id'])
            ->first();
        if ($info) {
            DB::beginTransaction();
            try{
                ja_comment_zan::find($info->id)->update([
                    "status"=>0
                ]);
                ja_comment::find($input['comment_id'])->decrement('zan_count');
                DB::commit();
                $result = $this->result('success','取消点赞成功!');
            }catch (\Exception $e) {
                //接收异常处理并回滚
                DB::rollBack();
                $result = $this->result('fail','ERROR!操作失败,请稍后再试!');
            }
        }else{
            $result = $this->result('fail','ERROR!数据错误,请重新刷新!');
        }
        return $result;
    }



    /**
 * 定义分类树函数
 *     @param     items         需要分类的二维数组 
 *     @param     $id         主键（唯一ID）
 *     @param     $pid     关联主键的PID
 *  @son 可以自定义往里面插入就行
 */
    public function catagory($items,$id='id',$pid='pid',$son = 'children'){
        $tree = array(); //格式化的树
        $tmpMap = array();  //临时扁平数据
     
        foreach ($items as $item) {
            $item[$son] = array();
            $tmpMap[$item[$id]] = $item;
        }
        // dd($tmpMap[$item[$id]]);
        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        unset($tmpMap);
        return $tree;
    }

    public function getComment()
    {
        $input = Input::all();
        $comment = ja_comment::where('community_id',$input['community_id'])
            ->where('status','1')
            ->get()->toArray();
        $data = array();
        $community = ja_community::find($input['community_id']);
        foreach ($comment as $key => $value) {
            if (ja_comment_zan::where('comment_id',$value['id'])
                ->where('to',$input['uuid'])
                ->where('status','1')
                ->first()) {
                $value['is_zan'] = true;
            }else{
                $value['is_zan'] = false;
            }
            $user = User::where('user_uuid',$value['uuid'])->first();
            $value['commment_user'] = $user['user_nickname'];
            $value['commment_user_pic'] = $user['user_pic'];
            $data[] = $value;
        }
        return $this->result('success','成功!',$this->catagory($data));
    }


    public function listFollow()
    {
        $input = Input::all();
        // 获取关注的列表
        $a = ja_follow::orderBy('ja_follow.id','DESC')
            ->where('ja_follow.uuid_a',$input['uuid'])
            // ->where('ja_follow.in_a',1)
            ->leftJoin('user','user.user_uuid','=','uuid_b')
            ->select('ja_follow.*','user.user_uuid','user.user_pic','user.user_nickname');
        $res = ja_follow::orderBy('ja_follow.id','DESC')
            ->where('ja_follow.uuid_b',$input['uuid'])
            // ->where('ja_follow.in_b',1)
            ->leftJoin('user','user.user_uuid','=','uuid_a')
            ->select('ja_follow.*','user.user_uuid','user.user_pic','user.user_nickname')
            ->union($a)
            ->get();
        // dd($res);
        $data = array();
        if ($res->isNotEmpty()) {
            foreach ($res as $key => $value) {
                if ($value->in_a + $value->in_b > 0) {
                    if ($value->uuid_a == $input['uuid']) {
                        if ($value->in_a == 1) {
                            $value->follow = $value->in_a;
                            $data[] = $value;
                        }else{
                            $value->follow = 0;
                            $data[] = $value;
                        }
                    }elseif ($value->uuid_b == $input['uuid']) {
                        if ($value->in_b == 1) {
                            $value->follow = $value->in_b;
                            $data[] = $value;
                        }else{
                            $value->follow = 0;
                            $data[] = $value;
                        }
                    }
                }
            }
        }
        return $this->result('success','获取数据成功!',$data);
    }

    // 添加屏蔽用户 -- 社区
    public function addNoLook()
    {
        $input = Input::all();
        // 写入屏蔽用户
        if (ja_no_look::where('uuid',$input['uuid'])
            ->where('to',$input['to'])
            ->first()) {
            if (ja_no_look::where('uuid',$input['uuid'])
            ->where('to',$input['to'])
            ->update([
                "status"=>1
            ])) {
                $result = $this->result('success','操作成功!');
            }else{
                $result = $this->result('fail','操作失败,请稍后再试!');
            }
        }else{
            $noLook = new ja_no_look;
            $noLook->uuid = $input['uuid'];
            $noLook->to = $input['to'];
            $noLook->type = 1;
            $noLook->status = 1;
            if ($noLook->save()) {
                $result = $this->result('success','操作成功!');
            }else{
                $result = $this->result('fail','操作失败,请稍后再试!');
            }
        }
        return $result;
    }

    // 获取屏蔽列表
    public function listNoLook()
    {
        $input = Input::all();
        $noLook = ja_no_look::orderBy('ja_no_look.id','DESC')
            ->where('ja_no_look.status','1')
            ->leftJoin('user','user.user_uuid','=','ja_no_look.to')
            ->select('user.user_uuid','user.user_pic','user.user_nickname')
            ->get();
        return $this->result('success','获取屏蔽列表成功!',$noLook);
    }

    // 取消屏蔽用户
    public function cancelNoLook()
    {
        $input = Input::all();
        // 取消屏蔽用户
        if (ja_no_look::where('uuid',$input['uuid'])
            ->where('to',$input['to'])
            ->update([
                "status"=>0
            ])
        ) {
            $result = $this->result('success','操作成功!');
        }else{
            $result = $this->result('fail','操作失败,请稍后再试!');
        }
        return $result;
    }
















}
