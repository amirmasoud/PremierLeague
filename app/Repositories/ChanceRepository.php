<?php

namespace App\Repositories;

use App\Club;

class ChanceRepository
{
    public function guess($points)
    {
        return round(($points / Club::get()->sum('points')) * 100, 2);
    }
}
