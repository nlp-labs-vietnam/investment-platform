<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRevision extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'payload'    => 'array',
        'created_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function editor()
    {
        return $this->belongsTo(Admin::class, 'editor_id');
    }
}
