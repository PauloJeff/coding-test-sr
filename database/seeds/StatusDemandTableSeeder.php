<?php

use Illuminate\Database\Seeder;

class StatusDemandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_demand')->insert([
            'name' => 'Novo'
        ]);

        DB::table('status_demand')->insert([
            'name' => 'Pago'
        ]);

        DB::table('status_demand')->insert([
            'name' => 'Entregue'
        ]);

        DB::table('status_demand')->insert([
            'name' => 'Cancelado'
        ]);
    }
}
