<?php

namespace App\Repositories;

use App\Club;
use App\Score;
use App\Match;

class ClubRepository
{
    public function goalsForSum(Club $club)
    {
        return $club->scores()->sum('score');
    }

    public function goalsAgainstSum(Club $club)
    {
        $awayMatches = $club->awayMatches()->get(['id']);
        $homeMatches = $club->homeMatches()->get(['id']);
        $awayMatchIds = $homeMatches->merge($awayMatches)->map(function($match) {
            return $match->id;
        });

        return Score::whereIn('match_id', $awayMatchIds)
            ->where('club_id', '!=', $club->id)
            ->sum('score');
    }

    public function goalsDifference(Club $club)
    {
        $df = $club->goals_for - $club->goals_against;
        return $df >= 0 ? '+' . $df : $df;
    }

    public function points(Club $club)
    {
        $points = 0;

        foreach ($club->homeMatches()->get() as $match) {
            $points += $this->pointCalculator($match, $club);
        }

        foreach ($club->awayMatches()->get() as $match) {
            $points += $this->pointCalculator($match, $club);
        }

        return $points;
    }

    public function won(Club $club)
    {
        $count = 0;

        foreach ($club->homeMatches()->get() as $match) {
            $count += $this->pointCalculator($match, $club) === 3 ? 1 : 0;
        }

        foreach ($club->awayMatches()->get() as $match) {
            $count += $this->pointCalculator($match, $club) === 3 ? 1 : 0;
        }

        return $count;
    }

    public function drawn(Club $club)
    {
        $count = 0;

        foreach ($club->homeMatches()->get() as $match) {
            $count += $this->pointCalculator($match, $club) === 1 ? 1 : 0;
        }

        foreach ($club->awayMatches()->get() as $match) {
            $count += $this->pointCalculator($match, $club) === 1 ? 1 : 0;
        }

        return $count;
    }

    public function lost(Club $club)
    {
        $count = 0;

        foreach ($club->homeMatches()->get() as $match) {
            $count += $this->pointCalculator($match, $club) === 0 ? 1 : 0;
        }

        foreach ($club->awayMatches()->get() as $match) {
            $count += $this->pointCalculator($match, $club) === 0 ? 1 : 0;
        }

        return $count;
    }

    public function played(Club $club)
    {
        return $club->scores()->count();
    }

    private function pointCalculator(Match $match, Club $club)
    {
        $scores = $match->scores;

        if ($scores->isEmpty()) {
            return null;
        }

        if ($scores->first()->club_id == $club->id) {
            return $this->pointPerMatch($scores->first()->score, $scores->last()->score);
        }

        if ($scores->last()->club_id == $club->id) {
            return $this->pointPerMatch($scores->last()->score, $scores->first()->score);
        }
    }

    private function pointPerMatch($clubScore, $oppositeClubScore)
    {
        if ($clubScore > $oppositeClubScore) {
            return 3;
        } elseif ($clubScore == $oppositeClubScore) {
            return 1;
        } else {
            return 0;
        }
    }
}
