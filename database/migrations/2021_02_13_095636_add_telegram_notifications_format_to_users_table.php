<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelegramNotificationsFormatToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('telegram_notifications_format')->nullable()->default('[[[[{"value":2,"text":"Artist Name (link)","key":"artist_name_link","style":"--tag-bg:hsl(76,48%,71%)","prefix":"@"}]]]] - [[[[{"value":4,"text":"Release Name (link)","key":"release_name_link","style":"--tag-bg:hsl(340,47%,66%)","prefix":"@"}]]]]');
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
            $table->dropColumn('telegram_notifications_format');
        });
    }
}
