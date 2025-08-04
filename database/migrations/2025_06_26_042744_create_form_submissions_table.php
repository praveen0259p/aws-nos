<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('Ngo_Unique_Id', 255);
            $table->string('Ack_Number');
            $table->integer('scheme_id');
            $table->integer('form_id');
            $table->integer('field_id');
            $table->longText('field_response');
            $table->integer('steps');
            $table->integer('user_id');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `form_submissions` MODIFY `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('from_submissions');
    }
};
