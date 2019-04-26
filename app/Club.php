<?php

namespace App;

use App\Match;
use App\Score;
use Illuminate\Database\Eloquent\Model;
use Facades\App\Repositories\ClubRepository;
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

    /**
     * Additional attributes.
     *
     * @var array
     */
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
        return ClubRepository::goalsForSum($this);
    }

    public function getGoalsAgainstAttribute(): int
    {
        return ClubRepository::goalsAgainstSum($this);
    }

    public function getGoalsDifferenceAttribute(): string
    {
        return ClubRepository::goalsDifference($this);
    }

    public function getPointsAttribute(): int
    {
        return ClubRepository::points($this);
    }

    public function getWonAttribute(): int
    {
        return ClubRepository::won($this);
    }

    public function getDrawnAttribute(): int
    {
        return ClubRepository::drawn($this);
    }

    public function getLostAttribute(): int
    {
        return ClubRepository::lost($this);
    }

    public function getPlayedAttribute()
    {
        return ClubRepository::played($this);
    }

    public function getChanceAttribute()
    {
        return ChanceRepository::guess($this->points);
    }
}
