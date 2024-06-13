<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 't_sales';
    protected $primaryKey = 'fc_salescode';
    public $incrementing = false;
}
