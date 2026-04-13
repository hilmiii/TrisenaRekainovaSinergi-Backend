<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // WAJIB DITAMBAHKAN AGAR DATA BISA DISIMPAN
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'company',
        'position',
        'address',
        'product_name',
        'material',
        'size',
        'color',
        'additional_notes',
        'quantity',
        'total_price',
        'status',
        'courier',
        'tracking_number'
    ];
}