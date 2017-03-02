<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class, 1)->create();
        \App\User::create([
            'name' => 'Paulo Caldeira',
            'email' => 'paulocaldeira17@gmail.com',
            'api_token' => '0e3f6930-ff94-11e6-bbe6-a137e846d20a'
        ])->save();
    }
}
