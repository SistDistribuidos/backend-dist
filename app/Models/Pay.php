<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pay extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'debt_id',
    ];

    public static function rules()
    {
        return [
            'amount' => 'required|numeric|gt:0',
            'debt_id' => 'required|numeric'
        ];
    }

    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }
}
