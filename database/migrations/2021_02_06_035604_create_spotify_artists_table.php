<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_artists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('spotify_id')->nullable();
            $table->text('spotify_uri')->nullable();
            $table->text('spotify_url')->nullable();
            $table->text('spotify_data')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_artists');
    }
}
