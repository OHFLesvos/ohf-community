<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePersonsBankTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('revoked_cards');
        Schema::dropIfExists('coupon_handouts');
        Schema::dropIfExists('coupon_types');
        Schema::dropIfExists('persons');
        Schema::dropIfExists('families');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_id', 32)->nullable();
            $table->bigInteger('family_id')->nullable()->unsigned();
            $table->string('name');
            $table->string('family_name')->nullable();
            $table->string('nickname')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['m', 'f'])->nullable();
            $table->string('card_no')->nullable();
            $table->timestamp('card_issued')->nullable();
            $table->unsignedInteger('police_no')->nullable();
            $table->string('remarks')->nullable();
            $table->string('nationality')->nullable();
            $table->string('languages')->nullable();
            $table->string('portrait_picture')->nullable();
            $table->string('search')->nullable();
            $table->foreign('family_id')->references('id')->on('families')->onDelete('set null')->onUpdate('cascade');
            $table->unique('public_id');
            $table->index('police_no', 'persons_police_no_index');
            $table->timestamps();
            $table->softDeletes();
            $table->index('search', 'persons_search_index');
        });

        Schema::create('coupon_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->unsignedInteger('daily_amount');
            $table->unsignedInteger('retention_period')->nullable();
            $table->unsignedInteger('min_age')->nullable();
            $table->unsignedInteger('max_age')->nullable();
            $table->unsignedInteger('daily_spending_limit')->nullable();
            $table->unsignedInteger('newly_registered_block_days')->nullable();
            $table->boolean('qr_code_enabled')->default(false);
            $table->unsignedInteger('code_expiry_days')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('returnable')->default(true);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->unique(['name']);
        });

        Schema::create('coupon_handouts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('amount');
            $table->integer('coupon_type_id')->unsigned();
            $table->integer('person_id')->unsigned();
            $table->date('code_redeemed')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
            $table->foreign('coupon_type_id')->references('id')->on('coupon_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['date', 'coupon_type_id', 'person_id']);
            $table->index(['person_id', 'date']);
            $table->index(['date', 'amount']);
        });
        // Fix issue with foreign key getting overwritten by composite key
        Schema::table('coupon_handouts', function (Blueprint $table) {
            $table->index('person_id', 'coupon_handouts_person_id_foreign');
        });

        Schema::create('revoked_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_no');
            $table->integer('person_id')->unsigned();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }
}
