<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory,HasUuids;
     protected $primaryKey = 'uuid';
     public $incrementing = false;
     protected $keyType = 'string';
   
     protected $hidden = [
        'created_at','updated_at'
     ];
 
}
