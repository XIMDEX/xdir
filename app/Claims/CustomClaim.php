<?php

namespace App\Claims;

use App\Models\Role;
use App\Models\Tool;
use App\Models\User;
use CorBosman\Passport\AccessToken;

class CustomClaim
{
    public function handle(AccessToken $token, $next)
    {
        $user = User::with(['roles.tools'])->find($token->getUserIdentifier());

        $rolesBitwiseMap = [
            'viewer' => '11100000',
            'creator' => '11110000',
            'editor' => '11111100',
            'admin' => '11111110',
            'superadmin' => '11111111',
        ];
        $toolsPermissions = [];

        foreach ($user->roles as $role) {
            $permission = $rolesBitwiseMap[strtolower($role->name)] ?? null; // Get the permission from the rolesBitwiseMap
            foreach ($role->tools as $tool) {
                $toolHash = $tool->hash; // Get the hash from the tool
                // Construct the organization#permission string
                $orgPermission = $permission . '#' . $role->pivot->organization_id;
                // Add to the array, grouping by tool_hash
                $toolsPermissions[$toolHash][] = $orgPermission;
            }
        }

        $roles = $user->roles()->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'tool_id' => $role->pivot->tool_id,
                'organization_id' => $role->pivot->organization_id,
                'tool_hash' => $role->tools->first()->hash
            ];
        })->all();

        $rolesArray = $user->roles->map(fn ($role) => $rolesBitwiseMap[$role->name])->all();


        $token->addClaim('p',  $toolsPermissions);
        return $next($token);
    }
}
