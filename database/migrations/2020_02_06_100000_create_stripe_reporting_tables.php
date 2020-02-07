<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeReportingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id');
            $table->string('stripe_charge_id');
            $table->string('invoice_id')->nullable();
            $table->integer('amount');
            $table->dateTime('date');
            $table->string('api_version')->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });

        Schema::create('stripe_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id');
            $table->string('stripe_invoice_id');
            $table->string('stripe_charge_id')->nullable();
            $table->integer('amount_due');
            $table->dateTime('date');
            $table->string('api_version')->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });

        Schema::create('stripe_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id',false, true);
            $table->string('item_id');
            $table->integer('amount');
            $table->text('description');
            $table->string('stripe_plan_id')->nullable();
            $table->string('plan_name')->nullable();
            $table->timestamps();

            //$table->foreign('invoice_id')->references('id')->on('stripe_invoices');
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_payments');

        Schema::dropIfExists('stripe_invoices');

        Schema::dropIfExists('stripe_invoice_items');
    }
}
