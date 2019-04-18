<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class test_rtsh_order extends Model
{
    protected $table = 'bbm_creditor_user_asset'; //设置表名
    protected $primaryKey = 'AssetsID'; //设置表的主键
    public $timestamps = false; //设置是否记录时间 flase为关闭
    protected $guarded = [];    //排除不能填充的字段 去除 _toke对象入库  MassAssignmentException in
}
