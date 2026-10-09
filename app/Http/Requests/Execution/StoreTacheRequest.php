<?php
namespace App\Http\Requests\Execution;

use App\Domain\Execution\Models\Tache;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTacheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Tache::class);
    }

    public function rules(): array
    {
        return [
            'phase_id'              => ['nullable', 'exists:phase_projets,id'],
            'nom'                   => ['required', 'string', 'max:255'],
            'description'           => ['nullable', 'string'],
            'assigne_a'             => ['nullable', 'exists:employees,id'],
            'date_debut'            => ['nullable', 'date'],
            'date_fin'              => ['nullable', 'date', 'after_or_equal:date_debut'],
            'duree_jours'           => ['nullable', 'integer', 'min:0'],
            'pourcentage_avancement'=> ['nullable', 'integer', 'between:0,100'],
            'priorite'              => ['nullable', Rule::in(['basse', 'normale', 'haute', 'critique'])],
            'statut'                => ['nullable', Rule::in(['a_faire', 'en_cours', 'termine'])],
        ];
    }
}