<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('organization_user', function (Blueprint $table) {
            $table->uuid('organization_uuid');
            $table->uuid('user_uuid');
            $table->foreign('organization_uuid')->references('uuid')->on('organizations')->onDelete('cascade');
            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->primary(['organization_uuid', 'user_uuid']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('organization_user');
    }
};
