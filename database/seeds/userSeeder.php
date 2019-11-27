<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        for($i = 0; $i<20;$i++){
            $user = new User();
            $user->name = $faker->userName;
            $user->email= $faker->email;
            $user->roles= 2;
            $user->password = bcrypt('password');
            $user->save();
        }

        $user = new User();
        $user->name = 'Admin';
        $user->email= 'admin@admin.com';
        $user->roles= 1;
        $user->password = bcrypt('password');
        $user->save();
    }
}
