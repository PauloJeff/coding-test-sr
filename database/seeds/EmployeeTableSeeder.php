<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employee')->insert([
            'store_id' => 1,
            'name' => 'Paulo Jefferson',
            'lastname' => 'Mendes Oliveira',
            'email' => 'paul_jeff@outlook.com',
            'cpf' => '00000000000',
            'rg' => '000000000',
            'password' => bcrypt('123qwe'),
        ]);
    }
}
