<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->unsignedBigInteger('users_id');
            $table->enum('type', ['rating', 'message']);
            $table->tinyInteger('rating')->unsigned()->nullable()->checkBetween(1, 5);
            $table->string('subject', 150)->nullable();
            $table->text('content');
            $table->timestamps();

            $table->foreign('users_id')->references('users_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
