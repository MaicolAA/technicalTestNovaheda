<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    protected $fillable = [
        'note',
        'noteable_id',
        'noteable_type',
    ];

    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }
}