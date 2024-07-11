<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('published');
            $table->string('buyer');
            $table->date('sold_date');
            $table->integer('kt');
            $table->integer('month');
            $table->integer('year');
            $table->integer('num_per_year');
            $table->integer('num_per_month');
            $table->date('pay_date');
            $table->boolean('payed');
            $table->float('total');
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
        //Schema::dropIfExists('bills');
    }
}