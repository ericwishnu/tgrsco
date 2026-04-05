<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'symbol',
        'decimal_places',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'decimal_places' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function variantPrices(): HasMany
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
