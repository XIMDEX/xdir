<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'email',
        'organization_id',
        'status',
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
}
