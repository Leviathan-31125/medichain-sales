<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSODTL extends Model
{
    use HasFactory;

    protected $table = 't_temp_sodtl';
    protected $primaryKey = 'fn_rownum';
    protected $guarded = [
        'fn_rownum',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public $incrementing = false;

    public function tempsomst () {
        return $this->hasOne(TempSOMST::class, 'fc_sono', 'fc_sono');
    }

    public function stock () {
        return $this->hasOne(Stock::class, 'fc_stockcode', 'fc_stockcode');
    }
}
