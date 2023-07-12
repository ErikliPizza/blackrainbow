<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
class CreateArtRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:24',
            'description' => 'required|max:255',
            'hours_spent' => 'integer|min:1|max:9999|nullable',
            'tags' => [
                'array',
                function ($attribute, $value, $fail) {
                    $tagNames = collect($value)->pluck('name');

                    if ($tagNames->count() !== $tagNames->unique()->count()) {
                        $fail('Your tags array has duplicated values.');
                        return;
                    }

                    $invalidTags = $tagNames->filter(function ($tagName) {
                        return !mb_ereg_match('^[[:alnum:]]+$', $tagName);
                    });

                    if ($invalidTags->isNotEmpty()) {
                        $fail('The following tag names must contain only alphabetic characters: ' . $invalidTags->implode(', '));
                    }
                },
            ],
            'styles' => 'array|required',
            'styles.*' => 'exists:styles,id',
            'images' => 'array|required'
        ];
    }
}
