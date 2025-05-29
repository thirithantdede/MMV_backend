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

    public static function boot(){
        parent::boot();
        static::creating(function ($model) {
            $model->readable_id = $model->generateReadableId();
        });
    }
    public function generateReadableId()
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $readableId = '';
        $length = 3; // 3 letters from A-Z
    
        for ($i = 0; $i < $length; $i++) {
            $readableId .= $alphabet[random_int(0, 25)];
        }
    
        $maxAttempts = 10;
        $attempt = 0;
    
        do {
            $random = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $candidateId = 'shop-id-' . $readableId . $random;
            $exists = static::where('readable_id', $candidateId)->exists();
            $attempt++;
    
            if ($attempt >= $maxAttempts) {
                throw new \Exception('Could not generate a unique readable ID after ' . $maxAttempts . ' attempts');
            }
        } while ($exists);
    
        return $candidateId;
    }
    

    public function getClosedDaysAttribute(): array | string
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
