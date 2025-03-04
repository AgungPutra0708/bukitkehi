<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_id')->nullable()->change();
            $table->unsignedBigInteger('facilities_id')->nullable()->after('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_id')->nullable(false)->change();
            $table->dropColumn('facilities_id');
        });
    }
};
