<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = "roles";

    public function users(){
        return $this->hasMany('App\Models\User');
    }
}
