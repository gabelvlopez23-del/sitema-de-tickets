<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'estado'
    ];

    // Relación inversa
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
