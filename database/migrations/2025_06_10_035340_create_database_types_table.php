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
        Schema::create('database_types', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('driver');
            $table->string('brand')->unique();
            $table->string('default_port')->nullable();
            $table->string('default_charset')->nullable();
            $table->string('default_collation')->nullable();
            $table->string('default_schema')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_types');
    }
};
