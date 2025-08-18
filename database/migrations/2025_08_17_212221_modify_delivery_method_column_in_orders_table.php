<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_method', 20)->change();
            // vagy ha ENUM-ot szeretnél:
            // $table->enum('delivery_method', ['delivery', 'pickup', 'dine-in'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_method', 5)->change(); // vagy az eredeti típus
        });
    }
};
