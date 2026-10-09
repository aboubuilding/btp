<?php
namespace App\Http\Requests\Stock;

use App\Domain\Approvisionnement\Models\MouvementStock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMouvementStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', MouvementStock::class);
    }

    public function rules(): array
    {
        return [
            'type'          => ['required', Rule::in(['entree', 'sortie', 'ajustement'])],
            'entrepot_id'   => ['required', 'exists:entrepots,id'],
            'materiau_id'   => ['required', 'exists:materiaux,id'],
            'quantite'      => ['required', 'numeric', 'gt:0'],
            'prix_unitaire' => ['nullable', 'numeric', 'min:0'],
            'projet_id'     => ['required_if:type,sortie', 'nullable', 'exists:projets,id'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'projet_id.required_if' => 'Toute sortie doit être imputée à un chantier (RG-S03).',
        ];
    }
}