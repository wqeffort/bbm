<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class test_user extends Model
{
    protected $table = 'mw_users'; //设置表名
    protected $primaryKey = 'user_id'; //设置表的主键
    public $timestamps = false; //设置是否记录时间 flase为关闭
    protected $guarded = [];    //排除不能填充的字段 去除 _toke对象入库  MassAssignmentException in
}
