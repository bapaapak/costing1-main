<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'deleted')) {
                $table->boolean('deleted')->default(false)->after('supplier');
            }

            if (!Schema::hasColumn('materials', 'block')) {
                $table->boolean('block')->default(false)->after('deleted');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'block')) {
                $table->dropColumn('block');
            }

            if (Schema::hasColumn('materials', 'deleted')) {
                $table->dropColumn('deleted');
            }
        });
    }
};
