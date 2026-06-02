<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->decimal('deviz_manopera', 10, 2)->nullable()->after('final_cost');
            $table->decimal('deviz_piese', 10, 2)->nullable()->after('deviz_manopera');
            $table->enum('deviz_status', ['trimis', 'aprobat', 'respins'])->nullable()->after('deviz_piese');
        });
    }

    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropColumn(['deviz_manopera', 'deviz_piese', 'deviz_status']);
        });
    }
};
