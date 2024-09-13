<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Apparels extends Model
{
    use HasFactory;

    protected $table = 'apparels';
    protected $fillable = ['budget_id', 'type', 'size', 'color', 'quantity', 'brand', 'price', 'style', 'remarks','user_id','purchase_date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apparelType()
    {
        return $this->belongsTo(ApparelType::class, 'type', 'id');
    }

    public function apparelStyle()
    {
        return $this->belongsTo(Style::class, 'style', 'id');
    }

    public function apparelBrand()
    {
        return $this->belongsTo(Brands::class, 'brand', 'id');
    }
}
