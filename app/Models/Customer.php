<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_customer';
    protected $primaryKey = 'fc_membercode';
    public $incrementing = false;
    public $guarded = [
        'fc_membercode',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function bank(){
        return $this->hasOne(TRXType::class, 'fc_trxcode', 'fc_memberbank');
    }
}
