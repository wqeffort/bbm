<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class bbm_discount extends Model
{
    protected $table = 'bbm_discount'; //设置表名
    protected $primaryKey = 'ID'; //设置表的主键
    public $timestamps = false; //设置是否记录时间 flase为关闭
    protected $guarded = [];    //排除不能填充的字段 去除 _toke对象入库  MassAssignmentException in
}
