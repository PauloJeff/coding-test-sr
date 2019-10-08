<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StoreTableSeeder::class);
        $this->call(EmployeeTableSeeder::class);
        $this->call(StatusDemandTableSeeder::class);
        $this->call(ClientTableSeeder::class);
    }
}
