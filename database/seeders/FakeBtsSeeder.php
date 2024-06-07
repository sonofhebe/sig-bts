<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class fakeBtsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create('id_ID');

        // foreach (range(1, 64) as $index) {
        //     DB::table('bts')->insert([
        //         'title' => $faker->company,
        //         'alamat' => $faker->address,
        //         'longitude' => $faker->longitude,
        //         'latitude' => $faker->latitude,
        //         'jumlah_antena' => $faker->numberBetween(2, 11),
        //         'frekuensi' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 1, $max = 2.9),
        //         'teknologi_jaringan' => $faker->randomElement($array = array('4G', '4.5G', '5G')),
        //         'luas_jaringan' => $faker->numberBetween(4, 15) . ' KM',
        //         'kapasitas_user' => $faker->numberBetween(100, 750),
        //         'informasi_lain' => null,
        //     ]);
        // }

        // Path to CSV file
        $file = database_path('seeds/bts.csv');

        // Open file for reading
        $handle = fopen($file, "r");

        // Loop through each row in the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Skip the header row
            if ($data[0] == 'id') {
                continue;
            }

            // Insert data into the database
            DB::table('bts')->insert([
                'title' => $data[1],
                'alamat' => $data[2],
                'longitude' => $data[3],
                'latitude' => $data[4],
                'jumlah_antena' => $data[5],
                'frekuensi' => $data[6],
                'teknologi_jaringan' => $data[7],
                'luas_jaringan' => $data[8],
                'kapasitas_user' => $data[9],
                'informasi_lain' => $data[10],
            ]);
        }

        // Close the file
        fclose($handle);
    }
}
