<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('label', 120);
            $table->string('route', 255)->nullable()->comment('Ruta React; null = solo agrupador');
            $table->string('icon', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('module_key', 80)->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['parent_id', 'sort_order', 'is_active']);
        });

        Schema::create('app_menu_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_menu_id')->constrained('app_menus')->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['app_menu_id', 'user_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_menu_user');
        Schema::dropIfExists('app_menus');
    }
};
