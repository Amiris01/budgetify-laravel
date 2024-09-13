<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budgets extends Model
{
    use HasFactory;

    protected $table = 'budgets';
    protected $fillable = ['user_id', 'title', 'category', 'remarks', 'total_amount', 'current_amount', 'start_date', 'end_date'];

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }
}
