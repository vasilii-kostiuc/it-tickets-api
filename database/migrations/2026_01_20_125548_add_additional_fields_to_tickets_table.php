<?php

use App\Domain\Department\Models\Department;
use App\Domain\Ticket\Enums\TicketSource;
use App\Domain\Ticket\Models\Sla;
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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('ticket_number')->unique();
            $table->foreignIdFor(\App\Domain\Ticket\Models\TicketStatus::class);
            $table->foreignIdFor(Department::class);
            $table->foreignIdFor(Sla::class);
            $table->foreignIdFor(\App\Domain\Ticket\Models\Category::class)->nullable();
            $table->string('source')->default(TicketSource::Other->value);

            $table->boolean('is_overdue')->default(false);
            $table->boolean('is_answered')->default(false);
            $table->dateTime('due_date')->nullable();
            $table->dateTime('est_due_date')->nullable();
            $table->dateTime('reopened_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('last_activity_at')->nullable();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'ticket_number',
                'ticket_status_id',
                'department_id',
                'sla_id',
                'category_id',
                'source',
                'is_overdue',
                'is_answered',
                'due_date',
                'est_due_date',
                'reopened_at',
                'closed_at',
                'last_activity_at',
            ]);
        });
    }
};
