<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        if (version_compare(Application::VERSION, '5.0', '>=')) {
            $this->tablename = config('settings.table');
            $this->keyColumn = config('settings.keyColumn');
            $this->valueColumn = config('settings.valueColumn');
        } else {
            $this->tablename = config('anlutro/l4-settings::table');
            $this->keyColumn = config('anlutro/l4-settings::keyColumn');
            $this->valueColumn = config('anlutro/l4-settings::valueColumn');
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop($this->tablename);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
            $table->increments('id');
            $table->string($this->keyColumn)->index();
            $table->text($this->valueColumn);
        });
    }
};
