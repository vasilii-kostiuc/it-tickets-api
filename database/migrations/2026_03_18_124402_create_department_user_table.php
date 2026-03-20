<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_user', function (Blueprint $table) {
            $table->foreignIdFor(\App\Domain\Department\Models\Department::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Domain\User\Models\User::class)->constrained()->cascadeOnDelete();
            $table->primary(['department_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_user');
    }
};
