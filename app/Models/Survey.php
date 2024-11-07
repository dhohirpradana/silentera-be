<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'pekerjaan',
        'jenis_kelamin',
        'umur',
        'informasi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
