<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilImage extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'user_id',
        'source', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
