<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // who applied
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // which job
            $table->foreignId('job_posting_id')->constrained()->cascadeOnDelete();

            // application status
            $table->string('status')->default('pending');
            // pending | reviewed | accepted | rejected

            $table->timestamp('applied_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
