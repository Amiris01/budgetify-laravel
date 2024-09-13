<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApparelType extends Model
{
    use HasFactory;

    protected $table = 'apparel_type';
    protected $fillable = ['name'];

    public function apparels(): BelongsTo
    {
        return $this->belongsTo(Apparels::class);
    }
}
