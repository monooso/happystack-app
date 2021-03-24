<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Constants\ToggleValue;
use App\Contracts\CreatesProjects;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Validators\CreateProjectValidator;
use Illuminate\Validation\ValidationException;

final class CreateProject implements CreatesProjects
{
    /**
     * Create a new project for the given user
     *
     * @param User  $user
     * @param array $input
     *
     * @return Project
     * @throws ValidationException
     */
    public function create(User $user, array $input): Project
    {
        // @todo Gate

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
     * @param array $input
     *
     * @return array
     * @throws ValidationException
     */
    private function validate(array $input): array
    {
        return CreateProjectValidator::make($input)->validate();
    }

    /**
     * Associate the selected components with the project
     *
     * @param Project $project
     * @param array   $input
     */
    private function createComponents(Project $project, array $input)
    {
        $project->components()->sync($input['components']);
    }

    /**
     * Create the project agency
     *
     * @param Project $project
     * @param array   $input
     */
    private function createAgency(Project $project, array $input)
    {
        $agency = $input['agency'];

        $project->agency()->create([
            'via_mail'   => $agency['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $agency['mail_route'] ?? '',
        ]);
    }

    /**
     * Create the project client
     *
     * @param Project $project
     * @param array   $input
     */
    private function createClient(Project $project, array $input)
    {
        $client = $input['client'];

        $project->client()->create([
            'via_mail'     => $client['via_mail'] === ToggleValue::ENABLED,
            'mail_route'   => $client['mail_route'] ?? '',
            'mail_message' => $client['mail_message'] ?? '',
        ]);
    }
}
