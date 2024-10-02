<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
        * Run the migrations.
        *
        * @return void
        */
        public function up()
        {
            Schema::create('invitations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('email');
                $table->string('status');
                $table->uuid('organization_id');
                $table->timestamps();
 
                // Foreign key constraint
                $table->foreign('organization_id')->references('uuid')->on('organizations')->onDelete('cascade');
            });
        }
 
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('invitations');
        }
};
