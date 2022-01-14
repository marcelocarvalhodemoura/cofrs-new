<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class banks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/seeders/banks.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                DB::table('banks')->insert([
                    'febraban_code' => $data['0'],
                    'name_bank' => $data['1'],
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
