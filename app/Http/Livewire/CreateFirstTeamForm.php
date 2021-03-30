<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\Redirector;

final class CreateFirstTeamForm extends Component
{
    use RedirectsActions;

    public array $state = [];

    /**
     * Create the user's first team
     *
     * @param CreatesTeams $creator
     *
     * @return RedirectResponse|Redirector
     */
    public function createTeam(CreatesTeams $creator): RedirectResponse | Redirector
    {
        $this->resetErrorBag();

        $creator->create(Auth::user(), $this->state);

        return $this->redirectPath($creator);
    }

    /**
     * Render the component
     *
     * @return View
     */
    public function render(): View
    {
        return view('teams.create-first-team-form');
    }
}
