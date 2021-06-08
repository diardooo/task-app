<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * atribut yang harus dilemparkan ke tipe asli.
     *
     * @var array
     */
    protected $casts = [
        'is_complete' => 'boolean',
    ];

    /**
     * Atribut yang dapat ditetapkan secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'is_complete',
    ];

    /**
     * Hubungan dengan pengguna yang memiliki.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
