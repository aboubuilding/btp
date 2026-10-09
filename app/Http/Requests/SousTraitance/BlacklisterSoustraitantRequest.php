<?php
namespace App\Http\Requests\SousTraitance;

use Illuminate\Foundation\Http\FormRequest;

class BlacklisterSoustraitantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('direction', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [
            'motif' => ['required', 'string', 'max:500'],
        ];
    }
}