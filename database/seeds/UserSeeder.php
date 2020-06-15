<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create([
            'name' => 'Rodrigo Caetano',
            'email' => 'admin@app.com',
            'password' => bcrypt('admin123'),
        ]);

        factory(User::class, 3)->create();
    }
}
