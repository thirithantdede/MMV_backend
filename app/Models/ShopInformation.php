<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInformation extends Model
{
    /** @use HasFactory<\Database\Factories\ShopInformationFactory> */
    use HasFactory,HasUlids;

}
