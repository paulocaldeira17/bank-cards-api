<?php

use Illuminate\Database\Seeder;

class CardTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\CardTransaction::class, 1)->create();
    }
}
