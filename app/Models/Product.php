<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'image_url', 
        'base_price', 'materials', 'sizes', 'colors', 'features', 'category'
    ];

    protected $casts = [
        'materials' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'features' => 'array',
    ];
}