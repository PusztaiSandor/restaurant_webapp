<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orders_id');
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('courier_id')->nullable();

            $table->enum('status', [
                'uj', 'keszul', 'atvetelre_kesz', 'atvetel_megtortent',
                'kiszallitva', 'lezarva', 'torolve'
            ])->default('uj');

            $table->enum('payment_method', ['bankkartya', 'keszpenz', 'szepkartya']);
            $table->enum('delivery_method', ['elvitel', 'helyben', 'kiszallitas']);

            $table->boolean('is_paid')->default(false);
            $table->timestamp('payment_time')->nullable();

            $table->decimal('delivery_fee', 6, 2)->default(0.00);
            $table->decimal('service_fee', 6, 2)->default(0.00);
            $table->decimal('discount', 6, 2)->default(0.00);

            $table->decimal('subtotal_net', 10, 2)->default(0.00);
            $table->decimal('subtotal_sum', 10, 2)->default(0.00);
            $table->decimal('total_tax', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);

            $table->integer('rating_star')->nullable();
            $table->text('rating_comment')->nullable();

            $table->timestamps();

            $table->foreign('users_id')->references('users_id')->on('users');
            $table->foreign('courier_id')->references('users_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
