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
        Schema::create('rule_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id');
            $table->foreignId('ad_id')->nullable();
            $table->text('message')->nullable();
            $table->text('error_message')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule_logs');
    }
};
