<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    protected $fillable = ['name', 'email', 'call_number', 'birth_date', 'address', 'address_complement', 'neighborhood', 'postal_address_code'];
    protected $dates = ['deleted_at'];
    
    use HasFactory, HasApiTokens, SoftDeletes;

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
