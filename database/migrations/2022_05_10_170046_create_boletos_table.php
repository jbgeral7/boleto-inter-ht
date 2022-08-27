<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoletosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->decimal('price', 10, 2)->index();
            $table->text('description', 255)->nullable();
            $table->string('my_number', 255)->index()->nullable()->comment('Número utilizado para controle interno');
            $table->string('our_number', 255)->index()->nullable()->comment('Nosso Número, atribuído automaticamente pelo banco na emissão do título.');
            $table->string('bar_code', 255)->index()->nullable()->comment('Representação numérica do código de barras do título emitido.');
            $table->string('digitable_line')->index()->nullable()->comment('Contém os mesmos dados do bar code dispostos em ordem diferente e acrescido de dígitos verificadores');
            $table->string('path')->nullable()->comment('Path onde será salvo o boleto');
            $table->string('boleto')->nullable()->coment('Nome dado ao boleto no monento do download');
            $table->date('due_date')->index();
            $table->enum('status', ['PAGO', 'EM ABERTO', 'CANCELADO', 'VENCIDO', 'EXPIRADO'])->index();
            $table->date('payment_date')->index()->nullable();
            $table->date('cancellation_date')->index()->nullable();
            $table->integer('date_to_pay_after_due_date');
            $table->timestamp('email_notify_send')->nullable();
            $table->timestamp('whatsapp_notify_send')->nullable();
            $table->timestamp('telegram_notify_send')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')
            ->references('id')
            ->on('customers')->onUpdated('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boletos');
    }
}
