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

    public function getOpeningHoursAttribute(): array
    {
        return json_decode($this->attributes['opening_hours']);
    }

    public function getSocialMediaAttribute(): array
    {
        return json_decode($this->attributes['social_media']);
    }

    public function getPromotionsAttribute(): array
    {
        return json_decode($this->attributes['promotions']);
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
