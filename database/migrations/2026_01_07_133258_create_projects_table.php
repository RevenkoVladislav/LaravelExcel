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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('types')->cascadeOnDelete();
            $table->string('title');
            $table->date('creation_date');
            $table->date('contracted_date');
            $table->date('deadline')->nullable();
            $table->boolean('is_chain')->nullable();
            $table->boolean('is_on_time')->nullable();
            $table->boolean('has_outsource')->nullable();
            $table->boolean('has_investors')->nullable();
            $table->integer('workers_count')->nullable();
            $table->integer('services_count')->nullable();


            $table->decimal('payment_first_step', 10, 2)->nullable();
            $table->decimal('payment_second_step', 10, 2)->nullable();
            $table->decimal('payment_third_step', 10, 2)->nullable();
            $table->decimal('payment_fourth_step', 10, 2)->nullable();

            $table->text('comment')->nullable();
            $table->decimal('efficiency_value', 5, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
