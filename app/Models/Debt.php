<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'amount',
        'state',
        'user_id',
    ];

    public static function rules()
    {
        return [
            'user_id' => 'required|numeric'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
    public function pays(): HasMany
    {
        return $this->hasMany(Pay::class);
    }
}
