<?php

namespace App;

use App\Match;
use App\Score;
use Illuminate\Database\Eloquent\Model;
use Facades\App\Repositories\ChanceRepository;

class Club extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'strength',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $appends = ['goals_for', 'goals_against', 'goals_difference',
        'points', 'played', 'won', 'drawn', 'lost', 'chance'];

    /**
     * Get the scores belongs to a club.
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Get the home matches belongs to a club.
     */
    public function homeMatches()
    {
        return $this->hasMany(Match::class, 'club_a');
    }

    /**
     * Get the away matches belongs to a club.
     */
    public function awayMatches()
    {
        return $this->hasMany(Match::class, 'club_b');
    }

    public function getGoalsForAttribute(): int
    {
        return $this->scores()->sum('score');
    }

    public function getGoalsAgainstAttribute(): int
    {
        $awayMatches = $this->awayMatches()->get(['id']);
        $homeMatches = $this->homeMatches()->get(['id']);
        $awayMatchIds = $homeMatches->merge($awayMatches)->map(function($match) {
            return $match->id;
        });

        return Score::whereIn('match_id', $awayMatchIds)
            ->where('club_id', '!=', $this->id)
            ->sum('score');
    }

    public function getGoalsDifferenceAttribute(): string
    {
        $df = $this->goals_for - $this->goals_against;
        return $df >= 0 ? '+' . $df : $df;
    }

    public function getPointsAttribute(): int
    {
        $points = 0;

        foreach ($this->homeMatches()->get() as $match) {
            $points += $this->pointCalculator($match);
        }

        foreach ($this->awayMatches()->get() as $match) {
            $points += $this->pointCalculator($match);
        }

        return $points;
    }

    public function getWonAttribute(): int
    {
        $count = 0;

        foreach ($this->homeMatches()->get() as $match) {
            $count += $this->pointCalculator($match) === 3 ? 1 : 0;
        }

        foreach ($this->awayMatches()->get() as $match) {
            $count += $this->pointCalculator($match) === 3 ? 1 : 0;
        }

        return $count;
    }

    public function getDrawnAttribute(): int
    {
        $count = 0;

        foreach ($this->homeMatches()->get() as $match) {
            $count += $this->pointCalculator($match) === 1 ? 1 : 0;
        }

        foreach ($this->awayMatches()->get() as $match) {
            $count += $this->pointCalculator($match) === 1 ? 1 : 0;
        }

        return $count;
    }

    public function getLostAttribute(): int
    {
        $count = 0;

        foreach ($this->homeMatches()->get() as $match) {
            $count += $this->pointCalculator($match) === 0 ? 1 : 0;
        }

        foreach ($this->awayMatches()->get() as $match) {
            $count += $this->pointCalculator($match) === 0 ? 1 : 0;
        }

        return $count;
    }

    private function pointCalculator(Match $match)
    {
        $scores = $match->scores;

        if ($scores->isEmpty()) {
            return null;
        }

        if ($scores->first()->club_id == $this->id) {
            return $this->pointPerMatch($scores->first()->score, $scores->last()->score);
        }

        if ($scores->last()->club_id == $this->id) {
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

    public function getPlayedAttribute()
    {
        return $this->scores()->count();
    }

    public function getChanceAttribute()
    {
        return ChanceRepository::guess($this->points);
    }
}
