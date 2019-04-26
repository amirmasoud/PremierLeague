<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     * Get the scores belongs to a club.
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
