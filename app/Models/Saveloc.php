<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;

class SaveLoc extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'lon', 
        'lat', 
        'region', 
        'province',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($user) {
    //         $user->{$user->getKeyName()} = Uuid::uuid4()->toString();
    //     });

    //     static::retrieved(function ($user) {
    //         $user->{$user->'id'} = Hash::make($user->{$user->'id'});
    //     });
    // }
}