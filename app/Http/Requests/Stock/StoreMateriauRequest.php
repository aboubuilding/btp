<?php
namespace App\Http\Requests\Stock;

use App\Domain\Approvisionnement\Models\Materiau;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMateriauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Materiau::class);
    }

    public function rules(): array
    {
        return [
            'code'                   => ['required', 'string', 'max:30', Rule::unique('materiaux', 'code')],
            'nom'                    => ['required', 'string', 'max:150'],
            'categorie_id'           => ['nullable', 'exists:categorie_materiaux,id'],
            'unite'                  => ['required', 'string', 'max:20'],
            'prix_unitaire'          => ['nullable', 'numeric', 'min:0'],
            'seuil_alerte_stock_min' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}