<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn(['flow_level', 'has_clots']);
        });
    }

    public function down(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->enum('flow_level', ['light','medium','heavy'])->nullable();
            $table->boolean('has_clots')->default(false);
        });
    }
};