<?php

namespace Tec\Installer\Http\Requests;

use Tec\Support\Http\Requests\Request;
use Tec\Theme\Facades\Manager;
use Illuminate\Validation\Rule;

class ChooseThemeRequest extends Request
{
    public function rules(): array
    {
        return [
            'theme' => ['required', 'string', Rule::in(array_keys(Manager::getThemes()))],
        ];
    }
}
