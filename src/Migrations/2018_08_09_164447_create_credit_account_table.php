<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->integer('balance')->default(0);
            $table->boolean('is_active')->default(true);
            $table->morphs('owner');
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
        Schema::dropIfExists('credit_accounts');
    }
}
