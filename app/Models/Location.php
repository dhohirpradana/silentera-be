<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['lat', 'lon', 'region', 'province'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
