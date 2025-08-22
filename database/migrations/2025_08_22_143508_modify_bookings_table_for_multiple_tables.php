<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Távolítjuk a régi idegen kulcsot és oszlopot
            $table->dropForeign(['tables_id']);
            $table->dropColumn('tables_id');

            // Új mező: több asztalkód tárolására
            $table->string('table_code', 100)->after('orders_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Visszaállítjuk az eredeti mezőt
            $table->dropColumn('table_code');

            $table->unsignedBigInteger('tables_id')->after('orders_id');
            $table->foreign('tables_id')->references('tables_id')->on('tables');
        });
    }
};
