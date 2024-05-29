<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids;
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'birthdate',
        'name',
        'surname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The organizations that the user belongs to.
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user', 'user_uuid', 'organization_uuid');
    }

    public function hasRoleInOrganization($role, $organizationId)
    {
        return $this->roles()->where('name', $role)->wherePivot('organization_id', $organizationId)->exists();
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_uuid', 'role_id')
            ->withPivot('organization_id')
            ->select('organization_id', 'role_id', 'name');
    }



    /**
     * Assign a role to the user within a specific organization.
     *
     * @param mixed $role
     * @param int $organizationId
     * @return $this
     */
    public function assignRoleWithOrganization($roles, $organizationId)
    {
        try {
            foreach ($roles as $role) {
                // Check if the role with the given organization_id already exists
                $exists = $this->roles()
                    ->wherePivot('organization_id', $organizationId)
                    ->where('role_id', $role)
                    ->exists();

                // Attach the role if it doesn't exist
                if (!$exists) {
                    $this->roles()->attach($role, [
                        'organization_id' => $organizationId,
                        'model_type' => get_class($this)
                    ]);
                }
            }
            // Detach roles that are not in the provided array of roles
            $rolesToDetach = $this->roles()
                ->wherePivot('organization_id', $organizationId)
                ->whereNotIn('role_id', $roles)
                ->get();

            foreach ($rolesToDetach as $role) {
                $this->roles()->detach($role->role_id, [
                    'organization_id' => $organizationId,
                    'model_type' => get_class($this)
                ]);
            }
        } catch (\Exception $e) {
            // Handle the exception (log it, rethrow it, or return a custom error message)
            \Log::error('Error attaching roles: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to assign roles'], 500);
        }
        return $this;
    }

    public function getAdditionalInformation()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
