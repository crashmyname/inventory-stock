<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningStock extends Model
{
    protected $table = 'opening_stock';
    protected $guarded = ['opening_stock_id'];
    protected $primaryKey = 'opening_stock_id';
}
