<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('product_unit')->insert([
            ['name' => 'Piece'],
            ['name' => 'gram'],
            ['name' => 'Kilogram'],
            ['name' => 'Liter'],
            // Add more units as needed
        ]);
    }
}
