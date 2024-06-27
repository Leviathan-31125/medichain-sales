<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_sales';
    protected $primaryKey = 'fc_salescode';
    public $incrementing = false;
    public $guarded = [
        'fc_salescode',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function bank(){
        return $this->hasOne(TRXType::class, 'fc_trxcode', 'fc_salesbank');
    }
}
