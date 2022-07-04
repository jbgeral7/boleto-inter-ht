<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialMediaConfigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media_configs', function (Blueprint $table) {
            $table->id();
            $table->text('whatsapp_token')->nullable();
            $table->text('whatsapp_session')->nullable();
            $table->text('whatsapp_init')->nullable();
            $table->text('whatsapp_secret_key')->nullable();;
            $table->text('whatsapp_authorization')->nullable();
            $table->text('whatsapp_extra_authorization')->nullable();
            $table->text('whatsapp_extra_authorization2')->nullable();
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
        Schema::dropIfExists('social_media_configs');
    }
}
