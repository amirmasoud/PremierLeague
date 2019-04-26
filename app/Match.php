<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'club_a', 'club_b',
    ];

    public function home()
    {
        return $this->belongsTo(Club::class, 'club_a', 'id');
    }

    public function away()
    {
        return $this->belongsTo(Club::class, 'club_b', 'id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
