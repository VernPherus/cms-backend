<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    use HasFactory;

    protected $fillable =[
        "fund_source",
        "time_received",
        "method",
        "lddap",
        "acic",
        "ors",
        "dv",
        "payee",
        "particulars",
        "gross_amount",
    ];
}
