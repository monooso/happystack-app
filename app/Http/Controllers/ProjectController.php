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
     * @return Response
     */
    public function create(): Response
    {
        return response()->view('projects.create');
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

    /**
     * Update the specified resource in storage.
     *
     * @param Request              $request
     * @param  Project $project
     *
     * @return Response
     */
    public function update(Request $request, Project $project): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project  $project
     *
     * @return Response
     */
    public function destroy(Project $project): Response
    {
        //
    }
}
