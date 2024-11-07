<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'feature'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
