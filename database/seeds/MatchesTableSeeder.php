<?php

use App\Club;
use Illuminate\Database\Seeder;

class MatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $matches = [];
        $clubs = Club::get();

        foreach ($clubs as $home) {
            foreach ($clubs as $away) {
                if ($home->id == $away->id) {
                    continue;
                }

                $matches[] = ['club_a' => $home->id, 'club_b' => $away->id];
            }
        }

        DB::table('matches')->insert($matches);
    }
}
