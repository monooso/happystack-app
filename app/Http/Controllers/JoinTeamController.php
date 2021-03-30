<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JoinTeamController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('teams.join');
    }
}
