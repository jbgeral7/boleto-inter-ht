<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->index();
            $table->string('description', 255)->nullable();
            $table->enum('status', [0,1])->index();
            $table->decimal('price', 10, 2)->index();
            $table->timestamps();
        });

        Schema::create('customer_service', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();

            $table->foreign('customer_id')
            ->references('id')
            ->on('customers')->onUpdated('cascade')->onDelete('cascade');
            
            $table->foreign('service_id')
            ->references('id')
            ->on('services')->onUpdated('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_service');
        Schema::dropIfExists('services');
    }
}
