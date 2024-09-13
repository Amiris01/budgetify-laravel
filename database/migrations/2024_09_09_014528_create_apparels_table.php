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
        Schema::create('apparels', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('budget_id');
            $table->string('type');
            $table->string('size');
            $table->string('color');
            $table->integer('quantity');
            $table->string('brand');
            $table->double('price');
            $table->string('style');
            $table->string('remarks');
            $table->date('purchase_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apparels');
    }
};
