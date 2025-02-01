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
        Schema::table('objek_pendukung', function (Blueprint $table) {
            $table->string('address')->after('latitude')->nullable();
            $table->text('description')->after('address')->nullable();
            $table->string('image')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objek_pendukung', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('description');
            $table->dropColumn('image');
        });
    }
};
