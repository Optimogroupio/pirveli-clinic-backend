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
        Schema::table('slider', function (Blueprint $table) {
            $table->text('title')->change();
            $table->enum('position', ['top', 'bottom'])->default('top')->after('description');
            $table->smallInteger('opens_modal')->nullable()->default(0)->after('position');
            $table->string('button_url')->nullable()->after('opens_modal');
            $table->string('button_title')->nullable()->after('button_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->string('title')->change();
            $table->dropColumn('position');
            $table->dropColumn('opens_modal');
            $table->dropColumn('button_url');
            $table->dropColumn('button_title');
        });
    }
};
