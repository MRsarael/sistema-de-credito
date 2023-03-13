<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCreditOfferModality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_offer_modality', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_financial_institution');
            $table->string('description');
            $table->string('cod');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_financial_institution')->references('id')->on('financial_institution');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_offer_modality', function($table){
            $table->dropForeign('credit_offer_modality_id_financial_institution_foreign');
        });

        Schema::dropIfExists('credit_offer_modality');
    }
}
