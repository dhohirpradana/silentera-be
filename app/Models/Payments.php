<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'lon', 
        'lat', 
        'region', 
        'province',
        'user_id',
        'lastday',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
