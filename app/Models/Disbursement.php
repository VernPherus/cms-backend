<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disbursement extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payee_id',
        'fund_source_id',
        'date_received',
        'date_entered',
        'particulars',
        'status',
        'method',
        'gross_amount',
        'total_deductions',
        'net_amount',
        'lddap_num',
        'acic_num',
        'ors_num',
        'dv_num',
        'uacs_code',
        'resp_code',
        'approved_at'
    ];

    protected $dates = ['date_received', 'date_entered', 'approved_at'];

    //* Relationship to payee
    public function payee(){
        return $this->belongsTo(Payee::class);
    }

    //* Relationship to fundSource
    public function fundSource()
    {
        return $this->belongsTo(FundSource::class);
    }

    //* Relationship to deductions
    public function deductions()
    {
        return $this->hasMany(DisbursementDeduction::class);
    }

    //* Relationship to items
    public function items()
    {
        return $this->hasMany(DisbursementItem::class);
    }
    
    //* helper function for computing total deductions automatically
    public static function boot(){
        parent::boot();

        static::saving(function($model){
            $model->net_amount=$model->gross_amount - $model->total_deductions;
        });
    }

    //* helper function to update totals, call whenever you save/add/delete an item or deduction
    public function recalculateTotals()
    {
        $this->gross_amount = $this->items()->sum('amount');
        $this->total_deductions = $this->deductions()->sum('amount');
        $this->net_amount = $this->gross_amount - $this->total_deductions;
        $this->save;
    }


}
