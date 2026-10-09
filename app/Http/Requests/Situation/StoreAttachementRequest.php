<?php
namespace App\Http\Requests\Situation;

use App\Domain\Execution\Models\Attachement;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttachementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Attachement::class);
    }

    public function rules(): array
    {
        return [
            'projet_id'                     => ['required', 'exists:projets,id'],
            'numero'                        => ['required', 'integer', 'min:1'],
            'periode_debut'                 => ['required', 'date'],
            'periode_fin'                   => ['required', 'date', 'after_or_equal:periode_debut'],
            'lignes'                        => ['required', 'array', 'min:1'],
            'lignes.*.ligne_devis_id'       => ['required', 'exists:ligne_devis,id'],
            'lignes.*.quantite_periode'     => ['required', 'numeric', 'gte:0'],
            'lignes.*.quantite_cumulee'     => ['required', 'numeric', 'gte:0'],
            'lignes.*.observation'          => ['nullable', 'string'],
        ];
    }
}