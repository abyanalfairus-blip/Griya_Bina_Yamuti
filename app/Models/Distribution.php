<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $guarded = [];
    protected $casts = ['distributed_at' => 'date'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
