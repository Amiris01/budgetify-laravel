<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brands extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $fillable = ['name'];
}
