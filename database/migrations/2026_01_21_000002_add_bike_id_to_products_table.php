<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('bike_id')->nullable()->after('category_id')->constrained()->onDelete('set null');
            $table->index('bike_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['bike_id']);
            $table->dropIndex(['bike_id']);
            $table->dropColumn('bike_id');
        });
    }
};
