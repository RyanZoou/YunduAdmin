<?php

namespace app\common\model;

use think\Model;

/**
 * 会员模型
 */
class Order Extends Model
{

    // 表名
    protected $name = 'ad_order';

    protected $pk = 'id';


    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

}
