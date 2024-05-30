<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'uuid';


    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'model_has_roles', 'role_id', 'tool_id');
    }
}