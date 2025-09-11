<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    protected $table = 'history_stock';
    protected $guarded = ['history_stock_id'];
    protected $primaryKey = 'history_stock_id';
}
