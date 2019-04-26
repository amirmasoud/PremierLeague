<?php

namespace App\Http\Controllers;

use Facades\App\Repositories\ScoreRepository;

class ScoreController extends Controller
{
    public function nextRound()
    {
        return ScoreRepository::nextRound();
    }

    public function allRounds()
    {
        return ScoreRepository::allRounds();
    }
}
