<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingFootprint extends Model
{
    /** @use HasFactory<\Database\Factories\BuildingFootprintFactory> */
    use HasFactory,HasUlids;

    protected $guarded = [];
}
