<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory,HasUlids;

    protected $guarded = [];
    
    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    public function getStartDateAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function getEndDateAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

}
