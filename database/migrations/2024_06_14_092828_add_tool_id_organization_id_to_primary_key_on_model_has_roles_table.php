<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['tool_id']);
            $table->dropForeign(['organization_id']);
           // $table->dropForeign(['model_uuid']);
            $table->dropPrimary(['model_type','model_uuid']);


            // Add the new composite primary key
            $table->primary(['role_id', 'model_uuid', 'model_type', 'tool_id', 'organization_id']);
            $table->foreign('role_id')->references('uuid')->on('roles');
            $table->foreign('tool_id')->references('uuid')->on('tools');
            $table->foreign('organization_id')->references('uuid')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            // Drop the new composite primary key
            $table->dropPrimary(['role_id', 'model_uuid', 'model_type', 'tool_id', 'organization_id']);

            // Re-add the old primary key
            $table->primary(['role_id', 'model_uuid', 'model_type']);
        });
    }
};
