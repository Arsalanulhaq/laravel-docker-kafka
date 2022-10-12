<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('member_id')->index()->nullable();
            // $table->foreign('member_id')
            //     ->references('id')->on('members')
            //     ->onDelete('cascade');
            
            $table->unsignedBigInteger('card_id')->index()->nullable();           
            // $table->unsignedBigInteger('card_id');
            // $table->foreign('card_id')
            //     ->references('id')->on('card_details')
            //     ->onDelete('cascade');
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
        Schema::dropIfExists('insurances');
    }
};
