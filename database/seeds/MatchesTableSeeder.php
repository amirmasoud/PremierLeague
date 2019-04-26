<?php

use App\Club;
use Illuminate\Database\Seeder;
use Laravel\RoundRobin\RoundRobin;

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

        $roundRobin = new RoundRobin(Club::get()->toArray());
        $roundRobin->doubleRoundRobin();
        $roundRobin = $roundRobin->build();
        $roundIterate = 1;
        foreach ($roundRobin as $round) {
            foreach ($round as $match) {
                $matches[] = [
                    'club_a' => $match[0]['id'],
                    'club_b' => $match[1]['id'],
                    'round'  => $roundIterate
                ];
            }
            $roundIterate++;
        }

        DB::table('matches')->insert($matches);
    }
}
