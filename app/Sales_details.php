<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales_details extends Model
{
    protected $table = 'sales_details';

    protected $primaryKey = 'id';
    public $timestamps = false;

    public function sale()
    {
        return $this->belongsTo('App\Sale', 'sales_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }


}
