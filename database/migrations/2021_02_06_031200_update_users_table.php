<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('spotify_access_token')->nullable();
            $table->text('spotify_refresh_token')->nullable();
            $table->text('spotify_data')->nullable();
            $table->text('telegram_chat_id')->nullable();
            $table->text('telegram_temp_code')->nullable();
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
            $table->dropColumn(['spotify_access_token', 'spotify_refresh_token', 'spotify_data', 'telegram_chat_id', 'telegram_temp_code']);
        });
    }
}
