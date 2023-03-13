<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePersonalCreditOffer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_credit_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_person');
            $table->unsignedBigInteger('id_credit_offer_modality');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_person')->references('id')->on('person');
            $table->foreign('id_credit_offer_modality')->references('id')->on('credit_offer_modality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_credit_offer', function($table){
            $table->dropForeign('personal_credit_offer_id_credit_offer_modality_foreign');
            $table->dropForeign('personal_credit_offer_id_person_foreign');
        });

        Schema::dropIfExists('personal_credit_offer');
    }
}
