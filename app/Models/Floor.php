<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    /** @use HasFactory<\Database\Factories\FloorFactory> */
    use HasFactory,HasUlids;

    protected $fillable = ['name', 'level', 'grid_size', 'walking_paths', 'project_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Summary of elements
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Element, $this>
     */
    public function elements(): HasMany
    {
        return $this->hasMany(Element::class, 'floor_id');
    }
}
