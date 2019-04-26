<?php

namespace App\Http\Controllers;

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
        return Score::get();
    }

    /**
     * Score a given round
     *
     * @return \Illuminate\Http\Response
     */
    public function round($round)
    {
        foreach (Match::whereRound($round)->get() as $match) {
            $awayClub = Match::whereRound($round)->first()->away;
            $homeClub = Match::whereRound($round)->first()->home;
            $awayWinningChance = (int) (rand(0, $awayClub->strength)/9);
            $homeWinningChance = (int) (rand(0, $homeClub->strength)/10);
            $awayClub->scores()->updateOrCreate(['match_id' => $match->id], [
                'match_id' => $match->id,
                'score'    => $awayWinningChance
            ]);
            $homeClub->scores()->updateOrCreate(['match_id' => $match->id], [
                'match_id' => $match->id,
                'score'    => $homeWinningChance
            ]);
        }

        return Score::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function show(Score $score)
    {
        return $score;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function edit(Score $score)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Score $score)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function destroy(Score $score)
    {
        //
    }
}
