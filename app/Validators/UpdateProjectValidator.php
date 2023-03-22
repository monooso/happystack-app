<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

final class UpdateProjectValidator extends ProjectValidator
{
    /**
     * Make a validator instance
     */
    public static function make(array $input): Validator
    {
        $messages = Lang::get('validation.custom.create_update_project');
        $flattened = Arr::dot($messages);

        return ValidatorFacade::make($input, self::rules(), $flattened);
    }
}
