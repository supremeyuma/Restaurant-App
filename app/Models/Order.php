<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'phone', 
        'amount', 
        'status', 
        'pickup_code',
        'note',
        'total',
        'payment_ref',
    ];


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
