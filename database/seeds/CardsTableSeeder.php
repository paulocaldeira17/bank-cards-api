<?php

use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Card::class, 1)->create();
        \App\Card::create([
            'accountName' => 'Master Card',
            'iban' => 'GB29 RBOS 6016 1331 9268 19',
            'bic' => 'MIDLGB22',
            'user_id' => \App\User::first()->id
        ])->save();
    }
}
