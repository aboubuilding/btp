<?php
namespace App\Http\Requests\Situation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachementRequest extends FormRequest
{
    public function authorize(): bool
    {
        $attachement = $this->route('attachement');
        return $this->user()->can('update', $attachement)
            && $attachement->statut !== 'valide';
    }

    public function rules(): array
    {
        return [
            'periode_debut'                 => ['required', 'date'],
            'periode_fin'                   => ['required', 'date', 'after_or_equal:periode_debut'],
            'lignes'                        => ['required', 'array', 'min:1'],
            'lignes.*.ligne_devis_id'       => ['required', 'exists:ligne_devis,id'],
            'lignes.*.quantite_periode'     => ['required', 'numeric', 'gte:0'],
            'lignes.*.quantite_cumulee'     => ['required', 'numeric', 'gte:0'],
        ];
    }
}