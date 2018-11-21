<?php

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
        // $this->call(UsersTableSeeder::class);
	    $this->call(GoodsTablesSeeder::class);
	    $this->call(PostageTablesSeeder::class);
	    $this->call(MenuSeeder::class);
    }
}
