<?php

namespace App\Http\Requests\Project;

use App\Enums\ImportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'import_type' => [
                'required',
                'string',
                Rule::enum(ImportType::class),
            ],
        ];
    }
}
