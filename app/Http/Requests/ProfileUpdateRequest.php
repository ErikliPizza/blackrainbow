<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'instagram' => ['nullable', 'regex:/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/'],
            'twitter' => ['nullable', 'regex:/^[A-Za-z0-9_]{1,15}$/'],
            'nso' => ['nullable', 'regex:/^[A-Za-z0-9_]{1,15}$/'],

        ];
    }
}
