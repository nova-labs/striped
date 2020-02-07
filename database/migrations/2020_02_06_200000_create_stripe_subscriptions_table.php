<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id');
            $table->string('type');
            $table->string('stripe_subscription_id');
            $table->string('stripe_plan_id')->nullable();
            $table->string('stripe_plan_id_previous')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->dateTime('date_anchor')->nullable();
            $table->string('api_version')->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_subscriptions');
    }
}
