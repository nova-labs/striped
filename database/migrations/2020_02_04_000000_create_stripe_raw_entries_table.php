<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeRawEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_raw_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('data');
            $table->boolean('valid');
            $table->boolean('processed')->default(false);
            $table->string('stripe_event_id')->nullable();
            $table->index('stripe_event_id');
            $table->string('name')->nullable();
            $table->dateTime('created')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('api_version')->nullable();
            $table->timestamps();

            $table->index(['processed', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_raw_entries');
    }
}
