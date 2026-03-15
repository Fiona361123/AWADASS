<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSeekerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->string('expected_salary', 100)->nullable();
            $table->string('education_university', 200)->nullable();
            $table->string('education_degree', 200)->nullable();
            $table->string('education_year', 10)->nullable();
            $table->string('work_company', 200)->nullable();
            $table->string('work_position', 200)->nullable();
            $table->string('work_years', 50)->nullable();
            $table->string('languages', 300)->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'expected_salary',
                'education_university',
                'education_degree',
                'education_year',
                'work_company',
                'work_position',
                'work_years',
                'languages',
            ]);
        });
    }
}
