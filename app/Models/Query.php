<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    //
    public function condition(){
        return $this->hasOne('App\Models\Condition');
    }
}
