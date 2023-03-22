<?php

declare(strict_types=1);

namespace App\Validators;

use App\Constants\ToggleValue;
use Illuminate\Validation\Rule;

abstract class ProjectValidator
{
    /**
     * Get the project validation rules
     */
    protected static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'components' => ['required', 'array', 'min:1'],
            'components.*' => ['exists:components,id'],

            'agency.via_mail' => ['required', Rule::in(ToggleValue::all())],

            'agency.mail_route' => [
                'required_if:agency.via_email,'.ToggleValue::ENABLED,
                'email',
                'max:255',
            ],

            'client.via_mail' => ['required', Rule::in(ToggleValue::all())],

            'client.mail_route' => [
                'required_if:client.via_mail,'.ToggleValue::ENABLED,
                'email',
                'max:255',
            ],

            'client.mail_message' => [
                'required_if:client.via_mail,'.ToggleValue::ENABLED,
                'string',
                'min:1',
                'max:60000',
            ],
        ];
    }
}
