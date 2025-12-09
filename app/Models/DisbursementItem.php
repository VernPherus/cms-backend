<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'disbursement_id', 
        'description', 
        'amount', 
        'account_code'
    ];

    public function disbursements(){
        return $this->hasMany(Disbursement::class);
    }
}
