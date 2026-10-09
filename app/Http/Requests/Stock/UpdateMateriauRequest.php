<?php
namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMateriauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('materiau'));
    }

    public function rules(): array
    {
        $materiauId = $this->route('materiau')->id;

        return [
            'code'                   => ['required', 'string', 'max:30', Rule::unique('materiaux', 'code')->ignore($materiauId)],
            'nom'                    => ['required', 'string', 'max:150'],
            'categorie_id'           => ['nullable', 'exists:categorie_materiaux,id'],
            'unite'                  => ['required', 'string', 'max:20'],
            'prix_unitaire'          => ['nullable', 'numeric', 'min:0'],
            'seuil_alerte_stock_min' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}