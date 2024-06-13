<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 't_customer';
    protected $primaryKey = 'fc_membercode';
    public $incrementing = false;
}
