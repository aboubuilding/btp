<?php
namespace App\Http\Requests\Execution;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTacheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tache'));
    }

    public function rules(): array
    {
        return [
            'nom'                   => ['required', 'string', 'max:255'],
            'description'           => ['nullable', 'string'],
            'assigne_a'             => ['nullable', 'exists:employees,id'],
            'date_debut'            => ['nullable', 'date'],
            'date_fin'              => ['nullable', 'date', 'after_or_equal:date_debut'],
            'pourcentage_avancement'=> ['nullable', 'integer', 'between:0,100'],
            'priorite'              => ['nullable', Rule::in(['basse', 'normale', 'haute', 'critique'])],
            'statut'                => ['nullable', Rule::in(['a_faire', 'en_cours', 'termine'])],
        ];
    }
}