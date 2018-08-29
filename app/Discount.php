<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
	protected $table = 'discounts';

    protected $primaryKey = 'id';
    public $timestamps = false;

    public function sale()
    {
        return $this->belongsTo('App\Sale', 'id', 'discount_id');
    }
}
