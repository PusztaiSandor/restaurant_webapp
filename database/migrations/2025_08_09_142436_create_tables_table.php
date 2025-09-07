<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id('tables_id');
            $table->string('table_code', 10)->unique();
            $table->string('location', 50)->default('beltér');
            $table->string('position', 50)->default('közép');
            $table->integer('capacity');
            $table->boolean('is_reservable')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
