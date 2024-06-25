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
            $orgPermission = $permission . '#' . $role->pivot->organization_id;
            $toolHash = $role->tools->first()->hash ?? null; // Get the tool's hash

            if ($permission && $toolHash) {
                $orgPermission = $permission . '#' . $role->pivot->organization_id;
                $toolsPermissions[$toolHash][] = $orgPermission; // Use $toolHash as the key
            }
        }

        $token->addClaim('p',  $toolsPermissions);
        return $next($token);
    }
}
