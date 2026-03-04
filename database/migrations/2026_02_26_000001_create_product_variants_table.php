<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('color');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('sku_suffix')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index(['product_id', 'type']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_variants');
        });

        Schema::dropIfExists('product_variants');
    }
};
