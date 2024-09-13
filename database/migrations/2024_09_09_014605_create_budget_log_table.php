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
        Schema::create('budget_log', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('budget_id');
            $table->double('amount_spent');
            $table->double('current_amount');
            $table->integer('id_ref');
            $table->string('table_ref');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_log');
    }
};
