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
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->boolean('correct')->default(0)->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('test_id')->nullable()->constrained();
            $table->foreignId('question_id')->nullable()->constrained();
            $table->foreignId('option_id')->nullable()->references('id')->on('question_options');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_answers');
    }
};
