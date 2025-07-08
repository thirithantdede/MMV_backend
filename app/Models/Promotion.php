<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasUlids;

    protected $fillable = [
        'element_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_featured'
    ];

    public function element()
    {
        return $this->belongsTo(Element::class);
    }
}
