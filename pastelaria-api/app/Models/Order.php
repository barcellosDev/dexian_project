<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $fillable = ['client_id', 'product_id', 'quantity'];
    protected $dates = ['deleted_at'];

    use HasFactory, HasApiTokens, SoftDeletes;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(OrderProduct::class)
            ->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($order) {
            if ($order->isForceDeleting()) {
                $order->products()->detach();
                $order->products()->forceDelete();
            } else {
                $order->products()->each(function ($product) {
                    $product->delete();
                });

                $order->products()->newPivotStatement()
                    ->where('order_id', $order->id)
                    ->update(['deleted_at' => now()]);
            }
        });
    }
}
