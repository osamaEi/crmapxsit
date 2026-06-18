<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop if exists from a previously failed migration run
        Schema::dropIfExists('lead_stage_history');

        Schema::create('lead_stage_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id');
            $table->unsignedInteger('stage_id')->nullable();
            $table->string('stage_name');
            $table->unsignedInteger('user_id')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_stage_history');
    }
};
