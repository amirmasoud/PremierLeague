<?php

namespace App\Repositories;

use App\Club;

class ChanceRepository
{
    public function guess($points)
    {
        if ($points == 0) {
            return 0;
        }

        return round(($points / Club::get()->sum('points')) * 100, 2);
    }
}
