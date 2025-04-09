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
        Schema::table('users', function(Blueprint $table){
            $table->enum('role', ['admin', 'teacher', 'student', 'parent'])->default('admin');
            $table->string('studentId')->unique()->nullable();
            $table->foreignId('student_id')->nullable()->constrained('students');
            $table->foreignId('parent_id')->nullable()->constrained('parents');
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('role');
            $table->dropColumn('studentId');
            $table->dropForeign('student_id');
            $table->dropForeign('parent_id');
        });
    }
};
