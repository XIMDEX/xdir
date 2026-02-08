<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationWithSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create an organization
        $organization = Organization::firstOrCreate(
            ['name' => 'XIMDEX'],
            ['uuid' => \Illuminate\Support\Str::uuid()]
        );

        $this->command->info("✅ Organization created: {$organization->name} (UUID: {$organization->uuid})");

        // 2. Create or get the superadmin role
        $superAdminRole = \App\Models\Role::firstOrCreate(
            ['name' => 'superadmin', 'guard_name' => 'api'],
            ['uuid' => \Illuminate\Support\Str::uuid()]
        );

        $this->command->info("✅ SuperAdmin role ready: {$superAdminRole->name}");

        // 3. Create a superadmin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@ximdex.com'],
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'name' => 'Super',
                'surname' => 'Admin',
                'password' => Hash::make('Admin12345'),
                'birthdate' => '1990-01-01',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("✅ SuperAdmin user created: {$user->email}");

        // 4. Attach user to organization
        $user->organizations()->syncWithoutDetaching([$organization->uuid]);

        $this->command->info("✅ User attached to organization");

        // 5. Create a general tool (or get existing) for the organization
        $tool = \App\Models\Tool::firstOrCreate(
            ['name' => 'xdir', 'type' => 'general'],
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'hash' => 'xdir-' . \Illuminate\Support\Str::random(8),
                'url' => 'http://localhost:8000',
                'status' => 'active',
                'description' => 'XDIR User Management System'
            ]
        );

        // 6. Assign superadmin role to user for this organization and tool
        // Check if the assignment already exists
        $existingRole = DB::table('model_has_roles')
            ->where('model_uuid', $user->uuid)
            ->where('role_id', $superAdminRole->uuid)
            ->where('organization_id', $organization->uuid)
            ->where('tool_id', $tool->uuid)
            ->first();

        if (!$existingRole) {
            DB::table('model_has_roles')->insert([
                'role_id' => $superAdminRole->uuid,
                'model_uuid' => $user->uuid,
                'model_type' => 'App\Models\User',
                'organization_id' => $organization->uuid,
                'tool_id' => $tool->uuid,
            ]);
            $this->command->info("✅ SuperAdmin role assigned to user in organization");
        } else {
            $this->command->info("ℹ️  SuperAdmin role already assigned");
        }

        $this->command->info("✅ Tool created: {$tool->name}");

        $this->command->newLine();
        $this->command->info('=================================');
        $this->command->info('🎉 SETUP COMPLETE!');
        $this->command->info('=================================');
        $this->command->info("📧 Email:    superadmin@ximdex.com");
        $this->command->info("🔑 Password: Admin12345");
        $this->command->info("🏢 Organization: {$organization->name}");
        $this->command->info("👤 Role: superadmin");
        $this->command->info('=================================');
    }
}
