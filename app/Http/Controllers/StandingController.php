<?php

namespace App\Http\Controllers;

use App\Club;

class StandingController extends Controller
{
    /**
     * Get all information about clubs standings.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return [
            'clubs'   => Club::get()
        ];
    }
}
