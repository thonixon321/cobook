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
        $locations = [
            [
                'name' => 'Mushroom Cottage',
                'address' => '123 Fake St. Coos Bay, OR 97420',
                'latitude' => '43.3665',
                'longitude' => '-124.2179',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Princess Palace',
                'address' => '321 Princess Rd. Bandon, OR 97411',
                'latitude' => '43.1190',
                'longitude' => '-124.4084',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
        
        $workshops = [
            [
                'location_id' => 1,
                'name' => "Tom's Cob Extravaganza",
                'startDate' => '2022-05-02',
                'endDate' => '2022-05-12',
                'description' => 'This will be an amazing cob learning experience!',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'location_id' => 2,
                'name' => "Becca's Mushroom Cottage",
                'startDate' => '2022-11-12',
                'endDate' => '2022-11-22',
                'description' => 'This will be a fun filled cob learning experience!',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach($locations as $location) {
            DB::table('locations')->insert($location);
        }

        foreach($workshops as $workshop) {
            DB::table('workshops')->insert($workshop);
        }
    }
}
