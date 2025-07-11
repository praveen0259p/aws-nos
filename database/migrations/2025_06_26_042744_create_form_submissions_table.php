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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('scheme_id');
            $table->integer('form_id');
            $table->integer('field_id');
            $table->string('field_response');
            $table->integer('user_id');
            // $table->rememberToken();
            // $table->string('api_token', 80)->unique()->nullable()->default(null);
            // $table->string('role')->default('user');
            // $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('from_submissions');
    }
};
