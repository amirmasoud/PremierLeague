<?php

namespace App\Http\Controllers;

use App\Club;
use App\Score;
use App\Match;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Score::orderBy('match_id')->get();
    }

    /**
     * Score a given round
     *
     * @return \Illuminate\Http\Response
     */
    public function round($round)
    {
        foreach (Match::whereRound($round)->get() as $match) {
            $awayClub = Club::find($match->club_b);
            $homeClub = Club::find($match->club_a);
            $awayWinningChance = (int) (rand(0, $awayClub->strength)/9);
            $homeWinningChance = (int) (rand(0, $homeClub->strength)/10);
            Score::updateOrCreate([
                'match_id' => $match->id,
                'club_id'  => $awayClub->id,
            ], [
                'match_id' => $match->id,
                'club_id'  => $awayClub->id,
                'score'    => $awayWinningChance,
            ]);
            Score::updateOrCreate([
                'match_id' => $match->id,
                'club_id'  => $homeClub->id,
            ], [
                'match_id' => $match->id,
                'club_id'  => $homeClub->id,
                'score'    => $homeWinningChance,
            ]);
        }

        return Score::orderBy('club_id')->get();
    }

    public function nextRound()
    {
        $toPlay = Score::max('match_id') ? Match::find(Score::max('match_id'))->round + 1 : 1;
        $matches = [];
        foreach (Match::whereRound($toPlay)->get() as $match) {
            $matches[] = $match->id;
            $awayClub = Club::find($match->club_b);
            $homeClub = Club::find($match->club_a);
            $awayWinningChance = (int) (rand(0, $awayClub->strength)/9);
            $homeWinningChance = (int) (rand(0, $homeClub->strength)/10);
            Score::updateOrCreate([
                'match_id' => $match->id,
                'club_id'  => $awayClub->id,
            ], [
                'match_id' => $match->id,
                'club_id'  => $awayClub->id,
                'score'    => $awayWinningChance,
            ]);
            Score::updateOrCreate([
                'match_id' => $match->id,
                'club_id'  => $homeClub->id,
            ], [
                'match_id' => $match->id,
                'club_id'  => $homeClub->id,
                'score'    => $homeWinningChance,
            ]);
        }

        return Match::whereIn('id', $matches)->with('home', 'away', 'scores')->get();
    }

    public function allRounds()
    {
        $allMatches = (Club::count() - 1) * 2;
        for ($i=0; $i < $allMatches; $i++) {
            $this->nextRound();
        }

        return [];
    }
}
