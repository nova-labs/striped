<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('email');
            $table->string('name');
            $table->string('member_type')->default('Guest');
            $table->string('old_username')->nullable();
            $table->string('password')->nullable();
            $table->string('old_password')->nullable();
            $table->integer('sponsor_id')->unsigned()->nullable();
            $table->text('notes')->nullable();
            $table->date('full_member_date')->nullable();
            $table->string('aspiration')->nullable();
            $table->string('meetup_id')->nullable();
            $table->string('badge_number')->nullable();
            $table->integer('family_primary_member_id')->unsigned()->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('phone', 15)->nullable();

            $table->string('stripe_subscription_plan')->nullable();

            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->dateTime('confirmed_at')->nullable();
            $table->string('confirmation_code')->nullable();

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
        Schema::dropIfExists('members');
    }
}
