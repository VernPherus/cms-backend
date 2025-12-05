<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deductions extends Model
{
    //* Relation to disbursements
    public function disbursements(){
        return $this->hasMany(Disbursement::class);
    }
}
