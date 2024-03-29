<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Constants\ToggleValue;
use App\Contracts\UpdatesProjects;
use App\Models\Project;
use App\Models\User;
use App\Validators\UpdateProjectValidator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

final class UpdateProject implements UpdatesProjects
{
    public function update(User $user, Project $project, array $input): Project
    {
        Gate::forUser($user)->authorize('update', $project);

        $input = $this->validate($input);

        $project->fill(['name' => $input['name']])->save();

        $this->updateComponents($project, $input);
        $this->updateAgency($project, $input);
        $this->updateClient($project, $input);

        return $project;
    }

    /**
     * Validate the project attributes
     *
     * @throws ValidationException
     */
    private function validate(array $input): array
    {
        return UpdateProjectValidator::make($input)->validate();
    }

    /**
     * Update the project components
     */
    private function updateComponents(Project $project, array $input): void
    {
        $project->components()->sync($input['components']);
    }

    /**
     * Update the project agency
     */
    private function updateAgency(Project $project, array $input): void
    {
        $payload = [
            'via_mail' => $input['agency']['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $input['agency']['mail_route'] ?? '',
        ];

        $project->agency
            ? $project->agency->fill($payload)->save()
            : $project->agency()->create($payload);
    }

    /**
     * Update the project client
     */
    private function updateClient(Project $project, array $input): void
    {
        $payload = [
            'via_mail' => $input['client']['via_mail'] === ToggleValue::ENABLED,
            'mail_route' => $input['client']['mail_route'] ?? '',
            'mail_message' => $input['client']['mail_message'] ?? '',
        ];

        $project->client
            ? $project->client->fill($payload)->save()
            : $project->client()->create($payload);
    }
}
