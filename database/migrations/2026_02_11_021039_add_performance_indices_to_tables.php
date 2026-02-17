<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Most critical indices - used in 90% of ProductListing queries
            $table->index(['category_id', 'is_active'], 'idx_products_category_active');
            $table->index(['price', 'is_active'], 'idx_products_price_active');

            // Secondary priority indices
            $table->index(['type', 'is_active'], 'idx_products_type_active');
            $table->index(['name', 'is_active'], 'idx_products_name_active');
        });

        Schema::table('bikes', function (Blueprint $table) {
            // Index for bike manufacturer filtering in ProductListing
            $table->index(['manufacturer', 'is_active'], 'idx_bikes_manufacturer_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_category_active');
            $table->dropIndex('idx_products_price_active');
            $table->dropIndex('idx_products_type_active');
            $table->dropIndex('idx_products_name_active');
        });

        Schema::table('bikes', function (Blueprint $table) {
            $table->dropIndex('idx_bikes_manufacturer_active');
        });
    }
};
