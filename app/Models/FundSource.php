<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FundSource extends Model
{
    use HasFactory;

    //* Relation to disbursements
    public function disbursements(){
        return $this->hasMany(Disbursement::class);
    }
}
