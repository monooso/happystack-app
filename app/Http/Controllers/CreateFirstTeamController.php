<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;

class CreateFirstTeamController extends Controller
{
    public function __invoke(Request $request): View
    {
        Gate::authorize('create', Jetstream::newTeamModel());

        return view('teams.create-first-team', ['user' => $request->user()]);
    }
}
