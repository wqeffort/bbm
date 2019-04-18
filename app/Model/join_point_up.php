<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class join_point_up extends Model
{
    protected $table = 'join_point_up'; //设置表名
    protected $primaryKey = 'id'; //设置表的主键
    public $timestamps = 'TRUE'; //设置是否记录时间 flase为关闭
    protected $guarded = [];    //排除不能填充的字段 去除 _toke对象入库  MassAssignmentException in
}
