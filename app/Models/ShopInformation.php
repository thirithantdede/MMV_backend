<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInformation extends Model
{
    /** @use HasFactory<\Database\Factories\ShopInformationFactory> */
    use HasFactory,HasUlids;

    protected $guarded = [];

    protected $appends = [
        'category',
    ];

    public function getClosedDaysAttribute(): array
    {
        $value = $this->attributes['closed_days'] ?? null;

        return is_string($value) ? json_decode($value, true) ?? [] : [];
    }

    public function getOpeningHoursAttribute(): array
    {
        $value = $this->attributes['opening_hours'] ?? null;

        return is_string($value) ? json_decode($value, true) ?? [] : [];
    }

    public function getCategoryAttribute()
    {
        return $this->storeCategory->name ?? null;
    }

    public function getSocialMediaAttribute(): array
    {
        $value = $this->attributes['social_media'] ?? null;

        return is_string($value) ? json_decode($value, true) ?? [] : [];
    }

    public function getPromotionsAttribute(): array
    {
        $value = $this->attributes['promotions'] ?? null;

        return is_string($value) ? json_decode($value, true) ?? [] : [];
    }

    /**
     * Summary of element
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Element, $this>
     */
    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    public function storeCategory()
    {
        return $this->belongsTo(StoreCategory::class);
    }

    public function scopeWithNonEmptyShopInfo($query)
    {
        return $query->whereRaw("JSON_LENGTH(shop_information) > 0");
    }
}
