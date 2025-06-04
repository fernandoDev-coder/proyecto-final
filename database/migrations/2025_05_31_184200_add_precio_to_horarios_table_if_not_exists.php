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
        if (!Schema::hasColumn('horarios', 'precio')) {
            Schema::table('horarios', function (Blueprint $table) {
                $table->decimal('precio', 10, 2)->default(0)->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('horarios', 'precio')) {
            Schema::table('horarios', function (Blueprint $table) {
                $table->dropColumn('precio');
            });
        }
    }
};
