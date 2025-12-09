<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisbursementDeduction extends Model
{
    protected $fillable = ['disbursement_id', 'deduction_type', 'amount'];

    //* Relation to disbursements
    public function disbursements(){
        return $this->hasMany(Disbursement::class);
    }

}
