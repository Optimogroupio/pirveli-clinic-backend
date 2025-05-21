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
            $table->string('title')->nullable()->change();
            $table->text('description')->nullable()->change();

            $table->dropColumn(['opens_modal', 'button_title']);

            $table->renameColumn('button_url', 'url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();

            $table->smallInteger('opens_modal')->nullable()->default(0)->after('position');
            $table->string('button_title')->nullable()->after('url');

            $table->renameColumn('url', 'button_url');
        });
    }
};
