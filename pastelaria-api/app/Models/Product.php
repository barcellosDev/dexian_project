<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'photo_src'];
    protected $dates = ['deleted_at'];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }

}
