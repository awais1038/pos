<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_unit')->insert([
            ['name' => 'Piece'],
            ['name' => 'gram'],
            ['name' => 'Kilogram'],
            ['name' => 'Liter'],
            // Add more units as needed
        ]);
    }
}
