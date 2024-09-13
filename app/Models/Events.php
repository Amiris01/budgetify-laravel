<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $fillable = ['user_id', 'name', 'location', 'status', 'remarks', 'attachment', 'start_timestamp', 'end_timestamp', 'expenses', 'income'];
}
