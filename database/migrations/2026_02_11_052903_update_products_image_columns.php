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
            // Keep existing image column for backward compatibility
            // Add new columns for enhanced image handling
            $table->string('image_local_path')->nullable()->after('image');
            $table->string('image_cdn_url')->nullable()->after('image_local_path');
            $table->json('image_metadata')->nullable()->after('image_cdn_url');
            $table->boolean('image_hosted_locally')->default(false)->after('image_metadata');
            $table->timestamp('image_synced_at')->nullable()->after('image_hosted_locally');

            // Add indexes
            $table->index('image_hosted_locally');
            $table->index('image_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['image_hosted_locally']);
            $table->dropIndex(['image_synced_at']);
            $table->dropColumn([
                'image_local_path',
                'image_cdn_url',
                'image_metadata',
                'image_hosted_locally',
                'image_synced_at',
            ]);
        });
    }
};
