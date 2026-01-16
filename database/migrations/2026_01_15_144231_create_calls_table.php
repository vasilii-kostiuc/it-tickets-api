<?php

use App\Domain\Call\Enums\CallType;
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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Domain\Ticket\Models\Ticket::class);
            $table->foreignIdFor(\App\Domain\Client\Models\Client::class);
            $table->enum('type', CallType::cases());
            $table->string('lang')->default('ro');
            $table->foreignIdFor(\App\Domain\User\Models\User::class);
            $table->string('extension')->unique();
            $table->string('unique_id')->unique();
            $table->dateTime('started');
            $table->dateTime('ended')->nullable();
            $table->string('duration')->nullable();
            $table->string('recording',1024)->nullable();
            $table->string('status',50)->nullable();
            $table->boolean('redirected')->default(false);
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
