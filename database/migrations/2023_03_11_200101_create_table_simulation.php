<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSimulation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simulation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_personal_credit_offer');
            $table->integer('min_installments');
            $table->integer('max_installments');
            $table->string('min_value');
            $table->string('max_value');
            $table->string('month_interest');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_personal_credit_offer')->references('id')->on('personal_credit_offer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simulation', function($table){
            $table->dropForeign('simulation_id_personal_credit_offer_foreign');
        });

        Schema::dropIfExists('simulation');
    }
}
