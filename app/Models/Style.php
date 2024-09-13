<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Style extends Model
{
    use HasFactory;

    protected $table = 'style';
    protected $fillable = ['name'];

    public function apparels(): BelongsTo
    {
        return $this->belongsTo(Apparels::class);
    }
}
