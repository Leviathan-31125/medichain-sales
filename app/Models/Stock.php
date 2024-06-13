<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 't_stock';
    protected $primaryKey = 'fc_barcode';
    public $guarded = [
        'fc_barcode',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public $incrementing = false;

    public function tempsodtl() {
        return $this->hasMany(TempSODTL::class, 'fc_barcode', 'fc_barcode');
    }
}
