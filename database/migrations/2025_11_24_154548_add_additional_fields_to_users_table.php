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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 32)->nullable()->after('name');
            $table->string('phone_ext', 32)->nullable();
            $table->string('mobile', 32)->nullable();
            $table->string('lang', 16)->nullable();
            $table->string('timezone', 256)->nullable();
            $table->string('locale', 256)->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->boolean('on_vocation')->default(false);

            $table->dateTime('last_login_at')->nullable();

        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'phone_ext',
                'mobile',
                'lang',
                'timezone',
                'locale',
                'is_active',
                'is_admin',
                'is_visible',
                'on_vocation',
                'last_login_at',
            ]);
        });
    }
};
