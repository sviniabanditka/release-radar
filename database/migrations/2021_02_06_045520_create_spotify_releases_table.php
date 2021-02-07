<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_releases', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('artist_id');
            $table->string('spotify_id')->nullable();
            $table->text('spotify_uri')->nullable();
            $table->text('spotify_url')->nullable();
            $table->text('spotify_data')->nullable();
            $table->timestamp('release_date')->nullable();
            $table->timestamps();
            $table->foreign('artist_id')->references('id')->on('spotify_artists')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_releases');
    }
}
