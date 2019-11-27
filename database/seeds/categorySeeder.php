<?php

use Illuminate\Database\Seeder;
use App\Models\Categories;

class categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $travel = new Categories();
        $travel->name = 'Travel';
        $travel->description = 'Daily Commute';
        $travel->save();


        $entertainment = new Categories();
        $entertainment->name = 'Entertainment';
        $entertainment->description = 'movies etc';
        $entertainment->save();
    }
}
