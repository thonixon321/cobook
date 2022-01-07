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
        \App\Models\User::factory(10)->create();
        \App\Models\Location::factory(5)->create();
        
        $workshops = [
            [
                'location_id' => 1,
                'name' => "Tom's Cob Extravaganza",
                'startDate' => '2022-05-02',
                'endDate' => '2022-05-12',
                'description' => 'This will be an amazing cob learning experience!'
            ],
            [
                'location_id' => 2,
                'name' => "Becca's Mushroom Cottage",
                'startDate' => '2022-11-12',
                'endDate' => '2022-11-22',
                'description' => 'This will be a fun filled cob learning experience!'
            ]
        ];
        
        foreach($workshops as $workshop) {
            DB::table('workshops')->insert($workshop);
        }
    }
}
