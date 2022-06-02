<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subcategories')->insert([
            'name' => 'Sottocategoria 1',
            'category_id' => 1,
        ]);

        DB::table('subcategories')->insert([
            'name' => 'Sottocategoria 2',
            'category_id' => 2,
        ]);

        DB::table('subcategories')->insert([
            'name' => 'Sottocategoria 3',
            'category_id' => 3,

        ]);
    }
}
