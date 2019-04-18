<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ja_no_look extends Model
{
    protected $table = 'ja_no_look'; //设置表名
    protected $primaryKey = 'id'; //设置表的主键
    public $timestamps = 'TRUE'; //设置是否记录时间 flase为关闭
    protected $guarded = [];    //排除不能填充的字段 去除 _toke对象入库
}
