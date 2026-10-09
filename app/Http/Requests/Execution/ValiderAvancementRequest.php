<?php
namespace App\Http\Requests\Execution;

use Illuminate\Foundation\Http\FormRequest;

class ValiderAvancementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('conducteur_travaux', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [
            'valide_le' => ['nullable', 'date'],
        ];
    }
}