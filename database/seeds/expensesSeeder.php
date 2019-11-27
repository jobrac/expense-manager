<?php

use Illuminate\Database\Seeder;
use App\Models\Expenses;

class expensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $travel1 = new Expenses();
        $travel1->user = 1;
        $travel1->category = 1;
        $travel1->amount = 230;
        $travel1->entry = date('Y-m-d', strtotime('2019-03-21'));
        $travel1->save();


        $travel2 = new Expenses();
        $travel2->user = 2;
        $travel2->category = 2;
        $travel2->amount = 20;
        $travel2->entry = date('Y-m-d', strtotime('2019-03-21'));
        $travel2->save();
    }
}
