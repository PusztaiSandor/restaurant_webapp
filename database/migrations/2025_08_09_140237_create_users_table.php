<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('users_id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('street_name', 100)->nullable();
            $table->string('street_number', 10)->nullable();
            $table->string('password');
            $table->string('password_hint')->nullable();
            $table->enum('role', ['user', 'courier', 'admin'])->default('user');
            $table->boolean('active')->default(true);
            $table->boolean('must_change_password')->default(false);
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
