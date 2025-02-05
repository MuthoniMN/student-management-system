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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('students', function(Blueprint $table){
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
        Schema::table('students', function(Blueprint $table){
            $table->dropColumn('grade_id');
        });
    }
};
