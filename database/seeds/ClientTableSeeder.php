<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('client')->insert([
            'name' => 'Paulo',
            'lastname' => 'Mendes',
            'cpf' => '00000000000',
            'rg' => '000000000'
        ]);
    }
}
