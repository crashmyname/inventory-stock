<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordTransaction extends Model
{
    protected $table = 'record_transaction';
    protected $guarded = ['record_transaction_id'];
    protected $primaryKey = 'record_transaction_id';
}
