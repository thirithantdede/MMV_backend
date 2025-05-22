<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'website', 'grid_size', 'business_hours', 'building_footprint',
        'is_published', 'published_at', 'user_id',
    ];

    protected $with = ['floors'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Summary of floors
     *
     * @return HasMany<Floor, $this>
     */
    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }

    /**
     * Summary of user
     *
     * @return HasOne<BuildingFootprint, $this>
     */
    public function buildingFootprint(): HasOne
    {
        return $this->hasOne(BuildingFootprint::class);
    }
}
