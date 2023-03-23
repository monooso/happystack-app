<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Validators\CreateProjectValidator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

final class CreateProject implements CreatesProjects
{
    /**
     * Create a new project for the given user
     *
     * @throws ValidationException
     */
    public function create(User $user, array $input): Project
    {
        Gate::forUser($user)->authorize('create', Project::class);

        $input = $this->validate($input);

        /** @var Team $team */
        $team = $user->currentTeam;

        /** @var Project $project */
        $project = $team->projects()->create(['name' => $input['name']]);

        $this->createComponents($project, $input);
        $this->createAgency($project, $input);
        $this->createClient($project, $input);

        return $project;
    }

    /**
     * Validate the project attributes
     *
     * @throws ValidationException
     */
    private function validate(array $input): array
    {
        return CreateProjectValidator::make($input)->validate();
    }

    /**
     * Associate the selected components with the project
     */
    private function createComponents(Project $project, array $input): void
    {
        $project->components()->sync($input['components']);
    }

    /**
     * Create the project agency
     */
    private function createAgency(Project $project, array $input): void
    {
        $agency = $input['agency'];

        $project->agency()->create([
            'via_mail' => $agency['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $agency['mail_route'] ?? '',
        ]);
    }

    /**
     * Create the project client
     */
    private function createClient(Project $project, array $input): void
    {
        $client = $input['client'];

        $project->client()->create([
            'via_mail' => $client['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $client['mail_route'] ?? '',
            'mail_message' => $client['mail_message'] ?? '',
        ]);
    }
}
