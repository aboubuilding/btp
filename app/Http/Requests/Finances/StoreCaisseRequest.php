<?php
namespace App\Http\Requests\Finances;

use App\Domain\Finances\Models\Caisse;
use Illuminate\Foundation\Http\FormRequest;

class StoreCaisseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Caisse::class);
    }

    public function rules(): array
    {
        return [
            'libelle'       => ['required', 'string', 'max:100'],
            'projet_id'     => ['nullable', 'exists:projets,id'],
            'solde_initial' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}