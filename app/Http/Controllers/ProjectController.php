<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ProjectController extends Controller
{
    /**
     * Display a list of team projects
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $team = $request->user()->currentTeam;

        return response()->view('projects.index', ['projects' => $team->projects]);
    }

    /**
     * Display the "create project" form
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
     * Display the project details
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
     * Display the "edit project" form
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
