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


    public function getClosedDaysAttribute(): array
    {
        $value = $this->attributes['closed_days'] ?? null;
        return is_string($value) ? json_decode($value, true) ?? [] : [];
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
}
