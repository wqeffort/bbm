<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class app_ad extends Model
{
    protected $table = 'app_ad'; //设置表名
    protected $primaryKey = 'id'; //设置表的主键
    public $timestamps = 'TRUE'; //设置是否记录时间 flase为关闭
    protected $guarded = [];    //排除不能填充的字段 去除 _toke对象入库
}
