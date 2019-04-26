<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function reset()
    {
        \Artisan::call('migrate:refresh', [
            '--force' => true,
            '--seed' => true
        ]);

        return [];
    }
}
