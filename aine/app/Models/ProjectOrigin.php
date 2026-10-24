<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectOrigin extends Model
{
    protected $fillable = [
        'origin',
        'abilities',
        'is_active',
        'description',
    ];

    protected $attributes = [
        'abilities' => '["read"]',
    ];

    protected $casts = [
        'abilities' => 'array',
        'is_active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
