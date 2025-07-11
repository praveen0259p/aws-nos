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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained()->onDelete('cascade');
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->json('header')->nullable();
            $table->string('label');
            $table->string('type'); 
            $table->string('name'); 
            $table->string('placeholder'); 
            $table->json('validation_rule');
            $table->json('front_validation_rule');
            $table->foreignId('option_id')->nullable()->constrained('options')->onDelete('set null');
            $table->integer('steps')->default(0);
            $table->integer('order')->default(0);
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
