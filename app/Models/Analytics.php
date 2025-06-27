<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    /** @use HasFactory<\Database\Factories\AnalyticsFactory> */
    use HasFactory,HasUlids;

    protected $guarded = [];

    public function element()
    {
        return $this->belongsTo(Element::class);
    }
}
