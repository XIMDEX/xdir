<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the table name from config
        $tableNames = config('permission.table_names');
        $rolesTable = $tableNames['roles'];

        // Insert the teacher role
        DB::table($rolesTable)->insert([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'teacher',
            'guard_name' => 'api',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get the table name from config
        $tableNames = config('permission.table_names');
        $rolesTable = $tableNames['roles'];

        // Remove the teacher role
        DB::table($rolesTable)->where('name', 'teacher')->delete();
    }
};
