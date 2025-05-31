<?php

namespace App\Models;

use App\Services\Element\StoreElement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'photo',
        'website', 'grid_size', 'business_hours', 'building_footprint',
        'is_published', 'published_at', 'user_id',
        'uri','is_public','current_version'
    ];

    protected $with = ['floors'];

    protected $appends = ['total_floors','photo_path'];

    protected $hidden = ['created_at', 'updated_at'];


    public function getViewCountAttribute()
    {
        return Cache::remember("project_view_count_{$this->id}", now()->addMinutes(15), function () {
            return $this->views()->count();
        });
    }

    public function getPhotoPathAttribute()
    {
        return asset('storage/project/cover/' . $this->photo);
    }

    public function getTotalFloorsAttribute()
    {
        return $this->floors->count();
    }

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

    public function elements(){
        return $this->hasMany(Element::class);
    }

    public function storeElements(){
        return $this->hasManyThrough(ShopInformation::class, Element::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(ProjectView::class);
    }
}
