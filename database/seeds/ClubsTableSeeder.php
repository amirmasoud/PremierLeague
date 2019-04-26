<?php

use Illuminate\Database\Seeder;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clubs')->insert([
            [
                'name' => 'Manchester City',
                'strength' => '97',
            ],
            [
                'name' => 'Liverpool',
                'strength' => '93',
            ],
            [
                'name' => 'Tottenham Hotspur',
                'strength' => '89',
            ],
            [
                'name' => 'Chelsea',
                'strength' => '85',
            ],
        ]);
    }
}
