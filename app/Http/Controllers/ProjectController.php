<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $team = auth()->user()->currentTeam;

        return response()->view('projects.index', ['projects' => $team->projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $user = $request->user();

        return response()->view('projects.create', [
            'user' => $user,
            'team' => $user->currentTeam,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     *
     * @return Response
     */
    public function show(Project $project): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     *
     * @return Response
     */
    public function edit(Project $project): Response
    {
        //
    }
}
