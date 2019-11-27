<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table = 'expenses';

    public function users(){
        return $this->hasOne('App\Models\User','id','user');
    }

    public function categories(){
        return $this->hasOne('App\Models\Categories','id','category');
    }
}
