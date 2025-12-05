<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundSource extends Model
{
    //* Relation to disbursements
    public function disbursements(){
        return $this->hasMany(Disbursement::class);
    }
}
