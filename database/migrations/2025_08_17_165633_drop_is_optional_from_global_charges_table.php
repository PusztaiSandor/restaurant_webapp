<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('global_charges', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });
    }

    public function down(): void
    {
        Schema::table('global_charges', function (Blueprint $table) {
            $table->boolean('is_optional')
                ->default(false)
                ->comment('1 = választható a felhasználó által, 0 = automatikusan alkalmazandó');
        });
    }
};
