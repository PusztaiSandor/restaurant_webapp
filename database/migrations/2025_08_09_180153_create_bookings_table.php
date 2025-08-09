<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('bookings_id');
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('orders_id')->nullable();
            $table->unsignedBigInteger('tables_id');

            $table->integer('seats');
            $table->dateTime('booking_time');

            $table->enum('status', ['uj', 'teljesitve', 'elutasitva', 'torolve'])->default('uj');

            $table->timestamps();

            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->foreign('orders_id')->references('orders_id')->on('orders')->onDelete('set null');
            $table->foreign('tables_id')->references('tables_id')->on('tables')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
