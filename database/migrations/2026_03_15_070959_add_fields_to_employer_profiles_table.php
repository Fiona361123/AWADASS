<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEmployerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::table('employer_profiles', function (Blueprint $table) {
        $table->string('address', 300)->nullable();
        $table->string('year_established', 10)->nullable();
        $table->string('linkedin')->nullable();
        $table->string('facebook')->nullable();
        $table->string('twitter')->nullable();
    });
}

public function down(): void
{
    Schema::table('employer_profiles', function (Blueprint $table) {
        $table->dropColumn([
            'address',
            'year_established',
            'linkedin',
            'facebook',
            'twitter',
        ]);
    });
}
}
