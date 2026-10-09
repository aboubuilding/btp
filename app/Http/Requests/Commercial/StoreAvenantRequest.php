<?php
namespace App\Http\Requests\Commercial;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('marche'));
    }

    public function rules(): array
    {
        return [
            'numero'         => ['required', 'string', 'max:50'],
            'objet'          => ['required', 'string', 'max:255'],
            'montant'        => ['nullable', 'numeric', 'min:0'],
            'delai_jours'    => ['nullable', 'integer', 'min:0'],
            'date_signature' => ['nullable', 'date'],
            'est_signe'      => ['nullable', 'boolean'],
        ];
    }
}