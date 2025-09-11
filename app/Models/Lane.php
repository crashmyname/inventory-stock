<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lane extends Model
{
    protected $table = 'lane';
    protected $guarded = ['lane_id'];
    protected $primaryKey = 'lane_id';
}
