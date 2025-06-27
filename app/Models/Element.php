<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// add old element id
/**
 * Summary of Element
 *
 * @property mixed $old_element_id
 */
class Element extends Model
{
    /** @use HasFactory<\Database\Factories\ElementFactory> */
    use HasFactory,HasUlids;

    protected $guarded = [];

    // get type attribute

    // append
    protected $appends = ['type', 'floor'];

    // hide floor relations
    protected $hidden = ['floor_id', 'element_type_id', 'floorRelation', 'elementType', 'created_at', 'updated_at'];

    public function getTypeAttribute(): string
    {
        return $this->elementType->name;
    }

    public function getBorderRadiusAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getFloorAttribute()
    {
        return $this?->floorRelation?->level ?? 0;
    }

    /**
     * @return BelongsTo<ElementType, $this>
     */
    public function elementType(): BelongsTo
    {
        return $this->belongsTo(ElementType::class);
    }

    public function storeCategory()
    {
        return $this->belongsTo(StoreCategory::class);
    }

    /**
     * @return BelongsTo<Floor, $this>
     */
    public function floorRelation(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function shopInformation()
    {
        return $this->hasOne(ShopInformation::class);
    }

    public function event(){
        return $this->hasOne(Event::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytics::class);
    }
}
