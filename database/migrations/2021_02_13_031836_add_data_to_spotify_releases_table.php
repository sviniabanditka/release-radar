<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToSpotifyReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spotify_releases', function (Blueprint $table) {
            $table->text('cover')->nullable();
            $table->string('album_group')->nullable();
            $table->string('album_type')->nullable();
            $table->text('artists')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spotify_releases', function (Blueprint $table) {
            $table->dropColumn(['cover', 'album_group', 'album_type', 'artists']);
        });
    }
}
