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
        Schema::create('database_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('database_type_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->string('db_name');
            $table->string('charset')->nullable();
            $table->string('collation')->nullable();
            $table->string('schema')->nullable();
            // $table->string('status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_connections');
    }
};
