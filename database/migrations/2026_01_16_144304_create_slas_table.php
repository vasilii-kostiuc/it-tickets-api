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

        Schema::create('slas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration');
            $table->foreignIdFor(\App\Domain\Schedule\Models\Schedule::class);
            $table->integer('grace_duration')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slas');
    }
};
