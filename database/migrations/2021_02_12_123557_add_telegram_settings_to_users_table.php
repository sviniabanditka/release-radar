<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelegramSettingsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('telegram_notifications_period')->nullable()->default('{"type": "day", "day": 0, "time": 12}');
            $table->text('telegram_notifications_types')->nullable()->default('{"album": 1, "single": 1, "appears_on": 0, "compilation": 0}');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_notifications_period', 'telegram_notifications_types']);
        });
    }
}
