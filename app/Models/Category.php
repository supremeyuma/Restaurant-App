<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Add 'name' here
        'position,'
        // Add any other fields you want to allow mass assignment for
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}
