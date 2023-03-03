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
        Schema::create('zip_codes', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('zip_code')->unique();
            $table->string('locality');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignUuid('federal_entity_id');
            $table->foreignUuid('municipality_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zip_codes');
    }
};
