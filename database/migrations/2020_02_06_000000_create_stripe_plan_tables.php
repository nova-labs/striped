<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripePlanTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id'); // i.e. prod_C8tjX9...TO8G
            $table->string('stripe_id'); // i.e. "storage.cabinet" or plan_GenOs4...mKh
            $table->integer('amount'); // assume $ in cents
            $table->string('name'); // "Storage – Cabinet" i.e. nickname
            $table->string('type'); // membership, incubator, other
            $table->boolean('active')->default(true);
            $table->boolean('stripe_active')->default(false);//
            $table->timestamps();
        });

        Schema::create('stripe_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id'); // i.e. prod_C8tjX9...TO8G
            $table->unique('product_id');

            $table->string('name'); // "Storage – Cabinet"
            $table->string('descriptor'); // statement descriptor
            $table->string('type'); // membership, incubator, other
            $table->boolean('active')->default(true);
            $table->boolean('stripe_active')->default(false);//
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
        Schema::dropIfExists('stripe_plans');
        Schema::dropIfExists('stripe_products');
    }
}
