<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reload_sale extends Model
{
    protected $table = 'reload_sales';

    protected $primaryKey = 'id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'member_id', 'id');
    }
}
