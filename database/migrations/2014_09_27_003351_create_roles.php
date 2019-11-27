<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [
                'name'          =>  'administrator',
                'description'   =>  'super user',
                'created_at' =>  date('Y-m-d'),
                'updated_at' =>  date('Y-m-d'),
            ],
            [
                'name'          =>  'user',
                'description'   =>  'can add expenses',
                'created_at' =>  date('Y-m-d'),
                'updated_at' =>  date('Y-m-d'),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
