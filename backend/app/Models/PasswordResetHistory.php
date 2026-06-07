<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetHistory extends Model
{
    public $timestamps = false;

    protected $table = 'password_reset_history';

    protected $fillable = [
        'user_id',
        'reset_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'reset_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
