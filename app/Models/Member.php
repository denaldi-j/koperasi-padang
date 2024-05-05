<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }
}
